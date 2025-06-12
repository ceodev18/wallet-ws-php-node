import * as dotenv from 'dotenv';
dotenv.config();

import express from 'express';
import swaggerUi from 'swagger-ui-express';
import swaggerSpec from './config/swagger';
import walletRoutes from './routes/wallet.routes';


const app = express();
app.use(express.json());

app.use('/api/wallet', walletRoutes);
app.use('/api/docs', swaggerUi.serve, swaggerUi.setup(swaggerSpec));

const PORT = process.env.PORT;
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
