/**
 * Copilot Sidecar - Express Server
 * Connects GitHub Copilot SDK to Carpathian CMS
 */

import express from 'express';
import cors from 'cors';
import 'dotenv/config';
import { createLogger, format, transports } from 'winston';
import { CmsClient } from './cms-client.js';
import { tools, executeToolCall } from './tools/index.js';

// Logger setup
const logger = createLogger({
  level: process.env.LOG_LEVEL || 'info',
  format: format.combine(
    format.timestamp(),
    format.json()
  ),
  transports: [
    new transports.Console(),
    new transports.File({ 
      filename: `${process.env.LOG_DIR || './logs'}/sidecar.log` 
    })
  ]
});

// Initialize CMS client
const cmsClient = new CmsClient({
  baseUrl: process.env.CMS_BASE_URL || 'http://127.0.0.1',
  apiToken: process.env.CMS_API_TOKEN,
  logger
});

// Express app
const app = express();
app.use(express.json({ limit: '10mb' }));
app.use(cors({
  origin: process.env.CMS_BASE_URL,
  credentials: true
}));

// Request logging middleware
app.use((req, res, next) => {
  logger.info('Request received', {
    method: req.method,
    path: req.path,
    ip: req.ip
  });
  next();
});

// Health check
app.get('/health', (req, res) => {
  res.json({ 
    status: 'ok', 
    timestamp: new Date().toISOString(),
    version: '1.0.0'
  });
});

// List available tools
app.get('/tools', (req, res) => {
  res.json({
    tools: Object.entries(tools).map(([name, tool]) => ({
      name,
      description: tool.description,
      parameters: tool.parameters
    }))
  });
});

// Chat endpoint (non-streaming)
app.post('/chat', async (req, res) => {
  try {
    const { message, sessionId, context } = req.body;
    
    if (!message) {
      return res.status(400).json({ error: 'Message is required' });
    }

    logger.info('Chat request', { sessionId, messageLength: message.length });

    // Process the message and determine which tools to call
    const response = await processMessage(message, context, cmsClient);
    
    res.json({
      sessionId: sessionId || generateSessionId(),
      response: response.text,
      toolCalls: response.toolCalls,
      timestamp: new Date().toISOString()
    });

  } catch (error) {
    logger.error('Chat error', { error: error.message, stack: error.stack });
    res.status(500).json({ 
      error: 'Internal server error',
      message: error.message 
    });
  }
});

// Execute a specific tool
app.post('/tools/:toolName/execute', async (req, res) => {
  try {
    const { toolName } = req.params;
    const { parameters } = req.body;

    if (!tools[toolName]) {
      return res.status(404).json({ error: `Tool '${toolName}' not found` });
    }

    logger.info('Tool execution', { toolName, parameters });

    const result = await executeToolCall(toolName, parameters, cmsClient);
    
    res.json({
      tool: toolName,
      result,
      timestamp: new Date().toISOString()
    });

  } catch (error) {
    logger.error('Tool execution error', { 
      error: error.message, 
      stack: error.stack 
    });
    res.status(500).json({ 
      error: 'Tool execution failed',
      message: error.message 
    });
  }
});

// Backup endpoint
app.post('/backup', async (req, res) => {
  try {
    const result = await cmsClient.runBackup();
    res.json(result);
  } catch (error) {
    logger.error('Backup error', { error: error.message });
    res.status(500).json({ error: error.message });
  }
});

// Restore endpoint
app.post('/restore{/:filename}', async (req, res) => {
  try {
    const { filename } = req.params;
    const result = filename 
      ? await cmsClient.restoreBackup(filename)
      : await cmsClient.restoreLatest();
    res.json(result);
  } catch (error) {
    logger.error('Restore error', { error: error.message });
    res.status(500).json({ error: error.message });
  }
});

// Message processing function
async function processMessage(message, context, client) {
  const lowerMessage = message.toLowerCase();
  const toolCalls = [];
  let responseText = '';

  // Simple intent matching - in production, use Copilot SDK for NLU
  if (lowerMessage.includes('list') && lowerMessage.includes('product')) {
    const result = await executeToolCall('listProducts', {}, client);
    toolCalls.push({ tool: 'listProducts', result });
    responseText = 'Found ' + (result.data?.length || 0) + ' products.';
  }
  else if (lowerMessage.includes('create') && lowerMessage.includes('product')) {
    responseText = 'To create a product, I need the following information: name, price, description, and optionally a category. Please provide these details.';
  }
  else if (lowerMessage.includes('backup')) {
    const result = await executeToolCall('runBackup', {}, client);
    toolCalls.push({ tool: 'runBackup', result });
    responseText = result.success ? 'Backup completed successfully!' : 'Backup failed: ' + result.error;
  }
  else if (lowerMessage.includes('restore')) {
    responseText = 'For safety, please confirm you want to restore the latest backup. Use the restore button or specify which backup to restore.';
  }
  else if (lowerMessage.includes('list') && lowerMessage.includes('page')) {
    const result = await executeToolCall('listPages', {}, client);
    toolCalls.push({ tool: 'listPages', result });
    responseText = 'Found ' + (result.data?.length || 0) + ' pages.';
  }
  else if (lowerMessage.includes('help')) {
    responseText = 'I can help you manage your CMS. Try these commands:\n' +
      '- "List all products" - Show all products\n' +
      '- "Create a product" - Add a new product\n' +
      '- "List all pages" - Show all pages\n' +
      '- "Run a backup" - Create a backup\n' +
      '- "Restore backup" - Restore from backup\n' +
      '- "Import products from CSV" - Bulk import products';
  }
  else {
    responseText = 'I understand you said: "' + message + '". I can help you manage products, pages, and backups. Type "help" for available commands.';
  }

  return { text: responseText, toolCalls };
}

// Session ID generator
function generateSessionId() {
  return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

// Error handler
app.use((err, req, res, next) => {
  logger.error('Unhandled error', { error: err.message, stack: err.stack });
  res.status(500).json({ error: 'Internal server error' });
});

// Start server
const PORT = process.env.PORT || 3001;
const HOST = process.env.HOST || '127.0.0.1';

app.listen(PORT, HOST, () => {
  logger.info('Copilot Sidecar running on http://' + HOST + ':' + PORT);
});

export { app, cmsClient };
