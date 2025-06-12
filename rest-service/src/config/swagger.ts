import swaggerJSDoc from 'swagger-jsdoc';

const options: swaggerJSDoc.Options = {
  definition: {
    openapi: '3.0.0',
    info: {
      title: 'Wallet API',
      version: '1.0.0',
    },
    components: {
      schemas: {
         RegisterClientDto: {
        type: 'object',
        properties: {
          document: { type: 'string', example: '12345678' },
          name: { type: 'string', example: 'Juan Pérez' },
          email: { type: 'string', format: 'email', example: 'juan@example.com' },
          phone: { type: 'string', example: '+51987654321' }
        },
        required: ['document', 'name', 'email', 'phone'],
        },
        LoadWalletDto: {
          type: 'object',
          properties: {
            clientId: { type: 'string' },
            amount: { type: 'number' },
          },
          required: ['clientId', 'amount'],
        },
        MakePurchaseDto: {
          type: 'object',
          properties: {
            clientId: { type: 'string' },
            amount: { type: 'number' },
          },
          required: ['clientId', 'amount'],
        },
        ConfirmPaymentDto: {
          type: 'object',
          properties: {
            clientId: { type: 'string' },
            transactionId: { type: 'string' },
          },
          required: ['clientId', 'transactionId'],
        },
        CheckBalanceDto: {
          type: 'object',
          properties: {
            clientId: { type: 'string' },
          },
          required: ['clientId'],
        },
      },
    },
  },
  apis: ['./src/routes/*.ts'],
};

export default swaggerJSDoc(options);
