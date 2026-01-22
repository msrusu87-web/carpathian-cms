#!/bin/bash

#############################################
# Carpathian CMS Backup & Restore Manager
# Safe deployment backup system for production
#############################################

BACKUP_DIR="/home/ubuntu/backups/carphatian-cms"
SITE_DIR="/home/ubuntu/live-carphatian"
DB_NAME="carpathian_db"
DB_USER="carpathian_user"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="backup_${TIMESTAMP}"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

#############################################
# Functions
#############################################

print_header() {
    echo -e "${BLUE}============================================${NC}"
    echo -e "${BLUE}  Carpathian CMS Backup Manager${NC}"
    echo -e "${BLUE}============================================${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

# Create backup directory if it doesn't exist
create_backup_dir() {
    if [ ! -d "$BACKUP_DIR" ]; then
        mkdir -p "$BACKUP_DIR"
        print_success "Created backup directory: $BACKUP_DIR"
    fi
}

# Create full backup
create_backup() {
    print_header
    print_info "Starting backup process..."
    echo ""
    
    create_backup_dir
    
    local backup_path="$BACKUP_DIR/$BACKUP_NAME"
    mkdir -p "$backup_path"
    
    # 1. Backup files
    print_info "Backing up files..."
    tar -czf "$backup_path/files.tar.gz" \
        --exclude='node_modules' \
        --exclude='vendor' \
        --exclude='storage/logs/*.log' \
        --exclude='storage/framework/cache' \
        --exclude='storage/framework/sessions' \
        --exclude='storage/framework/views' \
        -C "$SITE_DIR" .
    
    if [ $? -eq 0 ]; then
        print_success "Files backed up successfully"
    else
        print_error "Failed to backup files"
        return 1
    fi
    
    # 2. Backup database
    print_info "Backing up database..."
    
    # Try to get DB credentials from .env
    if [ -f "$SITE_DIR/.env" ]; then
        DB_NAME=$(grep DB_DATABASE "$SITE_DIR/.env" | cut -d '=' -f2)
        DB_USER=$(grep DB_USERNAME "$SITE_DIR/.env" | cut -d '=' -f2)
        DB_PASS=$(grep DB_PASSWORD "$SITE_DIR/.env" | cut -d '=' -f2)
    fi
    
    if [ -n "$DB_PASS" ]; then
        mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$backup_path/database.sql" 2>/dev/null
    else
        mysqldump -u "$DB_USER" "$DB_NAME" > "$backup_path/database.sql" 2>/dev/null
    fi
    
    if [ $? -eq 0 ]; then
        print_success "Database backed up successfully"
    else
        print_warning "Database backup failed or skipped"
    fi
    
    # 3. Backup .env file separately
    print_info "Backing up .env file..."
    if [ -f "$SITE_DIR/.env" ]; then
        cp "$SITE_DIR/.env" "$backup_path/env.backup"
        print_success ".env file backed up"
    fi
    
    # 4. Create backup manifest
    cat > "$backup_path/manifest.txt" << EOF
Backup Created: $(date)
Backup Name: $BACKUP_NAME
Site Directory: $SITE_DIR
Database: $DB_NAME
PHP Version: $(php -v | head -n 1)
Laravel Version: $(cd $SITE_DIR && php artisan --version)
EOF
    
    # 5. Calculate backup size
    local backup_size=$(du -sh "$backup_path" | cut -f1)
    
    echo ""
    print_success "Backup completed successfully!"
    print_info "Backup location: $backup_path"
    print_info "Backup size: $backup_size"
    print_info "Backup name: $BACKUP_NAME"
    
    # Keep only last 10 backups
    cleanup_old_backups
}

# Restore from backup
restore_backup() {
    print_header
    
    if [ -z "$1" ]; then
        print_error "No backup name provided"
        list_backups
        echo ""
        echo "Usage: $0 restore <backup_name>"
        exit 1
    fi
    
    local backup_path="$BACKUP_DIR/$1"
    
    if [ ! -d "$backup_path" ]; then
        print_error "Backup not found: $1"
        list_backups
        exit 1
    fi
    
    print_warning "You are about to restore from backup: $1"
    print_warning "This will overwrite current files and database!"
    echo ""
    read -p "Are you sure? (yes/no): " confirm
    
    if [ "$confirm" != "yes" ]; then
        print_info "Restore cancelled"
        exit 0
    fi
    
    print_info "Starting restore process..."
    echo ""
    
    # 1. Restore files
    print_info "Restoring files..."
    if [ -f "$backup_path/files.tar.gz" ]; then
        tar -xzf "$backup_path/files.tar.gz" -C "$SITE_DIR"
        print_success "Files restored successfully"
    else
        print_error "Files backup not found"
    fi
    
    # 2. Restore database
    print_info "Restoring database..."
    if [ -f "$backup_path/database.sql" ]; then
        # Get DB credentials
        if [ -f "$SITE_DIR/.env" ]; then
            DB_NAME=$(grep DB_DATABASE "$SITE_DIR/.env" | cut -d '=' -f2)
            DB_USER=$(grep DB_USERNAME "$SITE_DIR/.env" | cut -d '=' -f2)
            DB_PASS=$(grep DB_PASSWORD "$SITE_DIR/.env" | cut -d '=' -f2)
        fi
        
        if [ -n "$DB_PASS" ]; then
            mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$backup_path/database.sql" 2>/dev/null
        else
            mysql -u "$DB_USER" "$DB_NAME" < "$backup_path/database.sql" 2>/dev/null
        fi
        
        if [ $? -eq 0 ]; then
            print_success "Database restored successfully"
        else
            print_warning "Database restore failed or skipped"
        fi
    fi
    
    # 3. Restore .env if exists
    if [ -f "$backup_path/env.backup" ]; then
        cp "$backup_path/env.backup" "$SITE_DIR/.env"
        print_success ".env file restored"
    fi
    
    # 4. Clear caches
    print_info "Clearing caches..."
    cd "$SITE_DIR"
    php artisan cache:clear > /dev/null 2>&1
    php artisan config:clear > /dev/null 2>&1
    php artisan view:clear > /dev/null 2>&1
    print_success "Caches cleared"
    
    echo ""
    print_success "Restore completed successfully!"
    print_warning "Please verify your site is working correctly"
}

# List available backups
list_backups() {
    print_header
    print_info "Available backups:"
    echo ""
    
    if [ ! -d "$BACKUP_DIR" ] || [ -z "$(ls -A $BACKUP_DIR 2>/dev/null)" ]; then
        print_warning "No backups found"
        return
    fi
    
    # List backups with details
    for backup in $(ls -t "$BACKUP_DIR"); do
        local backup_path="$BACKUP_DIR/$backup"
        local backup_size=$(du -sh "$backup_path" | cut -f1)
        local backup_date=""
        
        if [ -f "$backup_path/manifest.txt" ]; then
            backup_date=$(grep "Backup Created:" "$backup_path/manifest.txt" | cut -d ':' -f2-)
        fi
        
        echo -e "${GREEN}$backup${NC}"
        echo "  Size: $backup_size"
        if [ -n "$backup_date" ]; then
            echo "  Date:$backup_date"
        fi
        echo ""
    done
}

# Delete old backups (keep last 10)
cleanup_old_backups() {
    local backup_count=$(ls -1 "$BACKUP_DIR" 2>/dev/null | wc -l)
    
    if [ "$backup_count" -gt 10 ]; then
        print_info "Cleaning up old backups (keeping last 10)..."
        ls -t "$BACKUP_DIR" | tail -n +11 | while read old_backup; do
            rm -rf "$BACKUP_DIR/$old_backup"
            print_info "Removed old backup: $old_backup"
        done
    fi
}

# Delete a specific backup
delete_backup() {
    if [ -z "$1" ]; then
        print_error "No backup name provided"
        echo "Usage: $0 delete <backup_name>"
        exit 1
    fi
    
    local backup_path="$BACKUP_DIR/$1"
    
    if [ ! -d "$backup_path" ]; then
        print_error "Backup not found: $1"
        exit 1
    fi
    
    print_warning "You are about to delete backup: $1"
    read -p "Are you sure? (yes/no): " confirm
    
    if [ "$confirm" = "yes" ]; then
        rm -rf "$backup_path"
        print_success "Backup deleted: $1"
    else
        print_info "Deletion cancelled"
    fi
}

# Show help
show_help() {
    print_header
    echo ""
    echo "Usage: $0 [command] [options]"
    echo ""
    echo "Commands:"
    echo "  backup         Create a new backup"
    echo "  restore <name> Restore from a specific backup"
    echo "  list           List all available backups"
    echo "  delete <name>  Delete a specific backup"
    echo "  help           Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0 backup"
    echo "  $0 list"
    echo "  $0 restore backup_20251223_120000"
    echo "  $0 delete backup_20251223_120000"
    echo ""
}

#############################################
# Main Script
#############################################

case "$1" in
    backup)
        create_backup
        ;;
    restore)
        restore_backup "$2"
        ;;
    list)
        list_backups
        ;;
    delete)
        delete_backup "$2"
        ;;
    help|--help|-h)
        show_help
        ;;
    *)
        show_help
        exit 1
        ;;
esac

exit 0
