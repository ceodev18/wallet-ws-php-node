import { Router } from 'express';
import {
  registerClient,
  checkBalance,
  loadWallet,
  makePurchase,
  confirmPayment
} from '../controllers/wallet.controller';

const router = Router();

/**
 * @openapi
 * /api/wallet/register:
 *   post:
 *     tags:
 *       - Wallet
 *     summary: Register a new client
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/RegisterClientDto'
 *     responses:
 *       200:
 *         description: Successful registration
 *         content:
 *           application/json:
 *             schema:
 *               $ref: '#/components/schemas/WalletResponse'
 */
router.post('/register', registerClient);
router.post('/balance', checkBalance);
router.post('/load', loadWallet);
router.post('/purchase', makePurchase);
router.post('/confirm', confirmPayment);

export default router;
