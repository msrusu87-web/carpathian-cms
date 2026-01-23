/**
 * CMS API Client
 * Handles all communication with the Carpathian CMS API
 */

import axios from 'axios';

export class CmsClient {
  constructor({ baseUrl, apiToken, logger }) {
    this.baseUrl = baseUrl;
    this.apiToken = apiToken;
    this.logger = logger;
    
    this.http = axios.create({
      baseURL: baseUrl + '/api/v1',
      headers: {
        'Authorization': 'Bearer ' + apiToken,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      timeout: 30000
    });

    // Response interceptor for error handling
    this.http.interceptors.response.use(
      response => response,
      async error => {
        if (error.response?.status === 500) {
          this.logger.error('Server error detected - considering rollback', {
            url: error.config?.url,
            status: error.response?.status
          });
        }
        throw error;
      }
    );
  }

  // Products API
  async listProducts(params = {}) {
    const response = await this.http.get('/products', { params });
    return response.data;
  }

  async getProduct(id) {
    const response = await this.http.get('/products/' + id);
    return response.data;
  }

  async createProduct(data) {
    const response = await this.http.post('/products', data);
    return response.data;
  }

  async updateProduct(id, data) {
    const response = await this.http.put('/products/' + id, data);
    return response.data;
  }

  async deleteProduct(id) {
    const response = await this.http.delete('/products/' + id);
    return response.data;
  }

  async importProducts(csvPath, options = {}) {
    const response = await this.http.post('/products/import', {
      csv_path: csvPath,
      ...options
    });
    return response.data;
  }

  async bulkUpdateProducts(updates) {
    const response = await this.http.patch('/products/bulk', { products: updates });
    return response.data;
  }

  // Pages API
  async listPages(params = {}) {
    const response = await this.http.get('/pages', { params });
    return response.data;
  }

  async getPage(id) {
    const response = await this.http.get('/pages/' + id);
    return response.data;
  }

  async createPage(data) {
    const response = await this.http.post('/pages', data);
    return response.data;
  }

  async updatePage(id, data) {
    const response = await this.http.put('/pages/' + id, data);
    return response.data;
  }

  async deletePage(id) {
    const response = await this.http.delete('/pages/' + id);
    return response.data;
  }

  // Backup API
  async runBackup() {
    const response = await this.http.post('/backups/run');
    return response.data;
  }

  async listBackups() {
    const response = await this.http.get('/backups');
    return response.data;
  }

  async restoreBackup(filename) {
    const response = await this.http.post('/backups/restore/' + filename);
    return response.data;
  }

  async restoreLatest() {
    const response = await this.http.post('/backups/restore-latest');
    return response.data;
  }

  async deleteBackup(filename) {
    const response = await this.http.delete('/backups/' + filename);
    return response.data;
  }

  // Job status API
  async getJobStatus(jobId) {
    const response = await this.http.get('/jobs/' + jobId + '/status');
    return response.data;
  }

  // Health check
  async healthCheck() {
    try {
      const response = await this.http.get('/health');
      return { healthy: true, ...response.data };
    } catch (error) {
      return { healthy: false, error: error.message };
    }
  }
}
