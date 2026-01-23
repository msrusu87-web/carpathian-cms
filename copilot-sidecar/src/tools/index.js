/**
 * Tool Definitions for Copilot Sidecar
 * Each tool maps to a CMS API operation
 */

import { z } from 'zod';

// Tool definitions with Zod schemas for validation
export const tools = {
  // Product Tools
  listProducts: {
    description: 'List all products with optional filtering',
    parameters: z.object({
      status: z.enum(['active', 'inactive', 'draft']).optional(),
      category_id: z.number().optional(),
      search: z.string().optional(),
      per_page: z.number().min(1).max(100).default(15),
      page: z.number().min(1).default(1)
    }).optional(),
    execute: async (params, client) => {
      return await client.listProducts(params || {});
    }
  },

  getProduct: {
    description: 'Get a specific product by ID',
    parameters: z.object({
      id: z.number().positive()
    }),
    execute: async (params, client) => {
      return await client.getProduct(params.id);
    }
  },

  createProduct: {
    description: 'Create a new product',
    parameters: z.object({
      name: z.string().min(1).max(255),
      price: z.number().positive(),
      description: z.string().optional(),
      status: z.enum(['active', 'inactive', 'draft']).default('draft'),
      category_id: z.number().optional(),
      sku: z.string().optional(),
      stock: z.number().int().min(0).optional()
    }),
    execute: async (params, client) => {
      return await client.createProduct(params);
    }
  },

  updateProduct: {
    description: 'Update an existing product',
    parameters: z.object({
      id: z.number().positive(),
      name: z.string().min(1).max(255).optional(),
      price: z.number().positive().optional(),
      description: z.string().optional(),
      status: z.enum(['active', 'inactive', 'draft']).optional()
    }),
    execute: async (params, client) => {
      const { id, ...data } = params;
      return await client.updateProduct(id, data);
    }
  },

  deleteProduct: {
    description: 'Delete a product by ID',
    parameters: z.object({
      id: z.number().positive()
    }),
    execute: async (params, client) => {
      return await client.deleteProduct(params.id);
    }
  },

  importProducts: {
    description: 'Import products from a CSV file',
    parameters: z.object({
      csv_path: z.string(),
      update_existing: z.boolean().default(false)
    }),
    execute: async (params, client) => {
      return await client.importProducts(params.csv_path, {
        update_existing: params.update_existing
      });
    }
  },

  // Page Tools
  listPages: {
    description: 'List all pages with optional filtering',
    parameters: z.object({
      status: z.enum(['published', 'draft', 'archived']).optional(),
      per_page: z.number().min(1).max(100).default(15),
      page: z.number().min(1).default(1)
    }).optional(),
    execute: async (params, client) => {
      return await client.listPages(params || {});
    }
  },

  getPage: {
    description: 'Get a specific page by ID',
    parameters: z.object({
      id: z.number().positive()
    }),
    execute: async (params, client) => {
      return await client.getPage(params.id);
    }
  },

  createPage: {
    description: 'Create a new page',
    parameters: z.object({
      title: z.string().min(1).max(255),
      content: z.string().optional(),
      status: z.enum(['published', 'draft', 'archived']).default('draft'),
      slug: z.string().optional()
    }),
    execute: async (params, client) => {
      return await client.createPage(params);
    }
  },

  updatePage: {
    description: 'Update an existing page',
    parameters: z.object({
      id: z.number().positive(),
      title: z.string().min(1).max(255).optional(),
      content: z.string().optional(),
      status: z.enum(['published', 'draft', 'archived']).optional(),
      slug: z.string().optional()
    }),
    execute: async (params, client) => {
      const { id, ...data } = params;
      return await client.updatePage(id, data);
    }
  },

  deletePage: {
    description: 'Delete a page by ID',
    parameters: z.object({
      id: z.number().positive()
    }),
    execute: async (params, client) => {
      return await client.deletePage(params.id);
    }
  },

  // Backup Tools
  runBackup: {
    description: 'Create a new backup of the CMS',
    parameters: z.object({}).optional(),
    execute: async (params, client) => {
      return await client.runBackup();
    }
  },

  listBackups: {
    description: 'List all available backups',
    parameters: z.object({}).optional(),
    execute: async (params, client) => {
      return await client.listBackups();
    }
  },

  restoreBackup: {
    description: 'Restore from a specific backup',
    parameters: z.object({
      filename: z.string()
    }),
    execute: async (params, client) => {
      return await client.restoreBackup(params.filename);
    }
  },

  restoreLatestBackup: {
    description: 'Restore from the most recent backup',
    parameters: z.object({}).optional(),
    execute: async (params, client) => {
      return await client.restoreLatest();
    }
  },

  // Job Tools
  getJobStatus: {
    description: 'Get the status of a background job',
    parameters: z.object({
      job_id: z.string()
    }),
    execute: async (params, client) => {
      return await client.getJobStatus(params.job_id);
    }
  }
};

/**
 * Execute a tool call
 */
export async function executeToolCall(toolName, parameters, client) {
  const tool = tools[toolName];
  
  if (!tool) {
    throw new Error('Unknown tool: ' + toolName);
  }

  // Validate parameters if schema exists
  let validatedParams = parameters;
  if (tool.parameters) {
    try {
      validatedParams = tool.parameters.parse(parameters);
    } catch (error) {
      throw new Error('Invalid parameters for ' + toolName + ': ' + error.message);
    }
  }

  // Execute the tool
  return await tool.execute(validatedParams, client);
}
