<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 50%, #dbeafe 100%);
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 24px; 
        }
        
        .card { 
            background: white; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 
                        0 10px 15px -3px rgba(0,0,0,0.08),
                        0 20px 25px -5px rgba(59, 130, 246, 0.1); 
            padding: 32px; 
            width: 100%; 
            max-width: 440px; 
            border: 1px solid #e2e8f0;
        }
        
        .header { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            margin-bottom: 28px; 
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .header-icon { 
            width: 48px; 
            height: 48px; 
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%); 
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            flex-shrink: 0; 
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        
        .header-icon svg { width: 24px; height: 24px; color: white; stroke: white; fill: none; }
        .header h1 { font-size: 20px; font-weight: 700; color: #0f172a; }
        .header p { font-size: 13px; color: #64748b; margin-top: 2px; }

        .section-title {
            font-size: 12px;
            font-weight: 600;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }

        .label { 
            display: block; 
            font-size: 13px; 
            font-weight: 500; 
            color: #374151; 
            margin-bottom: 6px; 
        }
        
        .field { margin-bottom: 16px; }

        .amount-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 8px; 
            margin-bottom: 10px; 
        }
        
        .amount-btn { 
            border: 1.5px solid #e2e8f0; 
            border-radius: 8px; 
            padding: 12px 0; 
            cursor: pointer; 
            background: white; 
            font-weight: 600; 
            font-size: 14px; 
            color: #475569; 
            font-family: 'Inter', sans-serif; 
            transition: all 0.15s ease; 
        }
        
        .amount-btn:hover { 
            border-color: #3b82f6; 
            color: #3b82f6; 
            background: #eff6ff; 
        }
        
        .amount-btn.active { 
            border-color: #3b82f6; 
            background: #3b82f6; 
            color: white;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        input[type="text"], input[type="number"] {
            width: 100%; 
            border: 1.5px solid #e2e8f0; 
            border-radius: 8px;
            padding: 12px 14px; 
            font-size: 14px; 
            font-family: 'Inter', sans-serif;
            color: #0f172a; 
            background: white; 
            outline: none; 
            transition: all 0.15s ease;
        }
        
        input:focus { 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); 
        }
        
        input::placeholder { color: #94a3b8; }
        input.error { border-color: #ef4444; }

        .stripe-field { 
            border: 1.5px solid #e2e8f0; 
            border-radius: 8px; 
            padding: 13px 14px; 
            background: white; 
            transition: all 0.15s ease; 
        }
        
        .stripe-field.focused { 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); 
        }
        
        .stripe-field.invalid { border-color: #ef4444; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .field-error { 
            font-size: 12px; 
            color: #ef4444; 
            margin-top: 4px; 
            min-height: 16px;
        }

        .test-cards-label { 
            font-size: 11px; 
            font-weight: 600; 
            color: #64748b; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            margin-bottom: 10px; 
        }
        
        .test-cards-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        
        .card-btn { 
            border: 1.5px solid #e2e8f0; 
            border-radius: 8px; 
            padding: 12px; 
            cursor: pointer; 
            background: white; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            gap: 4px; 
            transition: all 0.15s ease; 
            font-family: 'Inter', sans-serif; 
        }
        
        .card-btn:hover { 
            border-color: #3b82f6; 
            background: #f8fafc;
            transform: translateY(-1px);
        }
        
        .card-btn.active { 
            border-color: #3b82f6; 
            background: #eff6ff; 
        }
        
        .card-btn .status { font-size: 11px; font-weight: 600; }
        .card-btn .status.success { color: #10b981; }
        .card-btn .status.fail { color: #ef4444; }

        .hint-box { 
            margin-top: 10px; 
            background: #f8fafc; 
            border: 1.5px dashed #cbd5e1; 
            border-radius: 8px; 
            padding: 12px; 
            display: none; 
        }
        
        .hint-box.show { display: block; }
        
        .hint-top { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            margin-bottom: 6px; 
        }
        
        .hint-top span { font-size: 11px; color: #64748b; }
        
        .copy-btn { 
            background: white; 
            border: 1px solid #e2e8f0;
            border-radius: 6px; 
            padding: 4px 10px; 
            font-size: 11px; 
            color: #3b82f6; 
            cursor: pointer; 
            font-weight: 600; 
            font-family: 'Inter', sans-serif; 
            transition: all 0.15s;
        }
        
        .copy-btn:hover { 
            background: #3b82f6; 
            color: white;
            border-color: #3b82f6;
        }
        
        .hint-number { 
            font-family: 'SF Mono', monospace; 
            font-size: 15px; 
            font-weight: 600; 
            color: #0f172a; 
            margin-bottom: 4px; 
        }
        
        .hint-meta { 
            display: flex; 
            gap: 16px; 
            font-size: 12px; 
            color: #64748b; 
        }
        
        .hint-meta strong { color: #3b82f6; }

        .form-error { 
            background: #fef2f2; 
            border: 1px solid #fecaca; 
            border-radius: 8px; 
            padding: 12px 14px; 
            font-size: 13px; 
            color: #dc2626; 
            margin-bottom: 16px; 
            display: none; 
            align-items: center;
            gap: 8px;
        }
        
        .form-error.show { display: flex; }

        .pay-btn { 
            width: 100%; 
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%); 
            border: none; 
            border-radius: 10px; 
            padding: 14px; 
            color: white; 
            font-size: 15px; 
            font-weight: 600; 
            font-family: 'Inter', sans-serif; 
            cursor: pointer; 
            transition: all 0.15s ease; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .pay-btn:hover { 
            transform: translateY(-1px); 
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4); 
        }
        
        .pay-btn:disabled { 
            opacity: 0.7; 
            cursor: not-allowed; 
            transform: none; 
            box-shadow: none; 
        }

        .secure-badge { 
            text-align: center; 
            font-size: 12px; 
            color: #64748b; 
            margin-top: 16px; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .spinner { 
            width: 18px; 
            height: 18px; 
            border: 2px solid rgba(255,255,255,0.3); 
            border-top-color: white; 
            border-radius: 50%; 
            animation: spin 0.8s linear infinite; 
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }

        .divider { 
            border: none; 
            height: 1px; 
            background: #f1f5f9; 
            margin: 20px 0; 
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Header -->
        <div class="header">
            <div class="header-icon">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
            <div>
                <h1>Secure Payment</h1>
                <p>USD • SSL Encrypted</p>
            </div>
        </div>

        <form id="payment-form">
            @csrf

            <!-- Amount Section -->
            <div class="section-title">Payment Amount</div>
            <div class="field">
                <div class="amount-grid">
                    <button type="button" class="amount-btn" onclick="setAmount(10, this)">$10</button>
                    <button type="button" class="amount-btn" onclick="setAmount(25, this)">$25</button>
                    <button type="button" class="amount-btn" onclick="setAmount(50, this)">$50</button>
                    <button type="button" class="amount-btn" onclick="setAmount(100, this)">$100</button>
                </div>
                <input type="number" id="amount" name="amount" min="1" placeholder="Or enter custom amount..." step="0.01">
                <p class="field-error" id="amount-error"></p>
            </div>

            <hr class="divider">

            <!-- Card Details Section -->
            <div class="section-title">Card Information</div>
            
            <!-- Card Holder -->
            <div class="field">
                <label class="label">Card Holder Name</label>
                <input type="text" id="card-holder" name="card_holder" placeholder="John Doe" autocomplete="cc-name">
                <p class="field-error" id="card-holder-error"></p>
            </div>

            <!-- Card Number -->
            <div class="field">
                <label class="label">Card Number</label>
                <div id="card-number-element" class="stripe-field"></div>
                <p class="field-error" id="card-number-error"></p>
            </div>

            <!-- Expiry + CVC -->
            <div class="grid-2">
                <div class="field">
                    <label class="label">Expiry Date</label>
                    <div id="card-expiry-element" class="stripe-field"></div>
                    <p class="field-error" id="card-expiry-error"></p>
                </div>
                <div class="field">
                    <label class="label">CVC</label>
                    <div id="card-cvc-element" class="stripe-field"></div>
                    <p class="field-error" id="card-cvc-error"></p>
                </div>
            </div>

            <hr class="divider">

            <!-- Test Cards -->
            <div class="field">
                <p class="test-cards-label">Test Cards — Click to auto-fill</p>
                <div class="test-cards-grid">
                    <button type="button" class="card-btn" onclick="fillCard('visa')" id="btn-visa">
                        <svg viewBox="0 0 48 16" width="40" height="12"><text x="0" y="12" font-size="12" font-weight="bold" fill="#1a1f71" font-family="Arial">VISA</text></svg>
                        <span class="status success">Success</span>
                    </button>
                    <button type="button" class="card-btn" onclick="fillCard('mastercard')" id="btn-mastercard">
                        <div style="display:flex;align-items:center;">
                            <div style="width:16px;height:16px;border-radius:50%;background:#EB001B;"></div>
                            <div style="width:16px;height:16px;border-radius:50%;background:#F79E1B;margin-left:-7px;"></div>
                        </div>
                        <span class="status success">Success</span>
                    </button>
                    <button type="button" class="card-btn" onclick="fillCard('amex')" id="btn-amex">
                        <svg viewBox="0 0 48 16" width="44" height="12"><text x="0" y="12" font-size="10" font-weight="bold" fill="#007BC1" font-family="Arial">AMEX</text></svg>
                        <span class="status success">Success</span>
                    </button>
                    <button type="button" class="card-btn" onclick="fillCard('declined')" id="btn-declined">
                        <svg viewBox="0 0 64 16" width="56" height="12"><text x="0" y="12" font-size="10" font-weight="bold" fill="#dc2626" font-family="Arial">DECLINED</text></svg>
                        <span class="status fail">Fail</span>
                    </button>
                </div>

                <div id="card-hint" class="hint-box">
                    <div class="hint-top">
                        <span>Test card details</span>
                        <button type="button" class="copy-btn" id="copy-btn" onclick="copyNumber()">Copy</button>
                    </div>
                    <div class="hint-number" id="hint-number"></div>
                    <div class="hint-meta">
                        <span>Expiry: <strong id="hint-expiry"></strong></span>
                        <span>CVC: <strong id="hint-cvc"></strong></span>
                    </div>
                </div>
            </div>

            <!-- Form Error -->
            <div class="form-error" id="form-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span id="form-error-text"></span>
            </div>

            <!-- Pay Button -->
            <button type="submit" class="pay-btn" id="pay-button">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                <span id="pay-btn-text">Pay Now</span>
            </button>

            <p class="secure-badge">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                256-bit SSL Encryption
            </p>
        </form>
    </div>

    <script>
        const stripe = Stripe('{{ config("services.stripe.key") }}');
        const elements = stripe.elements({ locale: 'en' });

        const stripeStyle = {
            base: {
                fontSize: '14px',
                color: '#0f172a',
                fontFamily: "'Inter', sans-serif",
                '::placeholder': { color: '#94a3b8' }
            },
            invalid: { color: '#ef4444' }
        };

        const cardNumber = elements.create('cardNumber', { 
            style: stripeStyle, 
            showIcon: true,
            placeholder: '0000 0000 0000 0000'
        });
        const cardExpiry = elements.create('cardExpiry', { style: stripeStyle });
        const cardCvc = elements.create('cardCvc', { style: stripeStyle });

        cardNumber.mount('#card-number-element');
        cardExpiry.mount('#card-expiry-element');
        cardCvc.mount('#card-cvc-element');

        function bindStripeField(element, errorId, fieldId) {
            const fieldEl = document.getElementById(fieldId);
            const errorEl = document.getElementById(errorId);

            element.on('focus', () => {
                fieldEl.classList.add('focused');
                fieldEl.classList.remove('invalid');
            });

            element.on('blur', () => fieldEl.classList.remove('focused'));

            element.on('change', (event) => {
                if (event.error) {
                    errorEl.textContent = event.error.message;
                    fieldEl.classList.add('invalid');
                } else {
                    errorEl.textContent = '';
                    fieldEl.classList.remove('invalid');
                }
            });
        }

        bindStripeField(cardNumber, 'card-number-error', 'card-number-element');
        bindStripeField(cardExpiry, 'card-expiry-error', 'card-expiry-element');
        bindStripeField(cardCvc, 'card-cvc-error', 'card-cvc-element');

        const testCards = {
            visa: { number: '4242 4242 4242 4242', expiry: '12/34', cvc: '123' },
            mastercard: { number: '5555 5555 5555 4444', expiry: '12/34', cvc: '123' },
            amex: { number: '3782 822463 10005', expiry: '12/34', cvc: '1234' },
            declined: { number: '4000 0000 0000 0002', expiry: '12/34', cvc: '123' },
        };

        function fillCard(type) {
            document.querySelectorAll('.card-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById('btn-' + type).classList.add('active');
            
            const card = testCards[type];
            document.getElementById('hint-number').textContent = card.number;
            document.getElementById('hint-expiry').textContent = card.expiry;
            document.getElementById('hint-cvc').textContent = card.cvc;
            document.getElementById('card-hint').classList.add('show');
            document.getElementById('card-holder').value = 'Test User';
            cardNumber.focus();
        }

        function copyNumber() {
            const num = document.getElementById('hint-number').textContent.replace(/\s/g, '');
            navigator.clipboard.writeText(num).then(() => {
                const btn = document.getElementById('copy-btn');
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 1500);
            });
        }

        function setAmount(val, btn) {
            document.getElementById('amount').value = val;
            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('amount-error').textContent = '';
        }

        function showError(msg) {
            document.getElementById('form-error-text').textContent = msg;
            document.getElementById('form-error').classList.add('show');
        }

        function hideError() {
            document.getElementById('form-error').classList.remove('show');
        }

        function setLoading(loading) {
            const btn = document.getElementById('pay-button');
            if (loading) {
                btn.disabled = true;
                btn.innerHTML = '<div class="spinner"></div> Processing...';
            } else {
                btn.disabled = false;
                btn.innerHTML = `
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    <span id="pay-btn-text">Pay Now</span>
                `;
            }
        }

        document.getElementById('payment-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            hideError();

            const amount = document.getElementById('amount').value;
            const holder = document.getElementById('card-holder').value.trim();

            if (!amount || parseFloat(amount) < 1) {
                showError('Please enter a valid amount');
                return;
            }
            if (!holder) {
                showError('Please enter card holder name');
                return;
            }

            setLoading(true);

            try {
                const { paymentMethod, error: pmError } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardNumber,
                    billing_details: { name: holder }
                });

                if (pmError) {
                    showError(pmError.message);
                    setLoading(false);
                    return;
                }

                const formData = new FormData();
                formData.append('_token', document.querySelector('[name=_token]').value);
                formData.append('amount', amount);
                formData.append('card_holder', holder);
                formData.append('payment_method_id', paymentMethod.id);

                const response = await fetch('{{ route("checkout.process") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showError(data.error || 'Payment failed');
                    setLoading(false);
                }

            } catch (err) {
                showError('An error occurred. Please try again.');
                setLoading(false);
            }
        });
    </script>
</body>
</html>