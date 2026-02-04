
   @php
       $bkash_gateway = \App\Models\PaymentGateway::where(['status'=> 1, 'type'=>'bkash'])->first();
        $shurjopay_gateway = \App\Models\PaymentGateway::where(['status'=> 1, 'type'=>'shurjopay'])->first();
        $paystation_gateway = \App\Models\PaymentGateway::where(['status'=> 1, 'type'=>'paystation'])->first();
   @endphp

 <style>
 .payment-section {
            background: white;
            padding: 15px;
            border-radius: 8px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
        }

        .payment-option {
            position: relative;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 12px 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .payment-option:hover {
            border-color: #28a745;
            background: #f8fff9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .payment-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .payment-option.selected {
            border-color: #28a745;
            background: #f0f9f4;
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.2);
        }

        .payment-icon {
            width: 35px;
            height: 35px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .p_cash .payment-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .p_bkash .payment-icon {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
        }

        .p_shurjo .payment-icon {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        .p_paystation .payment-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .payment-option:hover .payment-icon {
            transform: scale(1.05);
        }

        .payment-label {
            cursor: pointer;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            flex: 1;
            line-height: 1.3;
        }

        .payment-option.selected .payment-label {
            color: #28a745;
            font-weight: 600;
        }

        .check-mark {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #28a745;
            color: white;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
        }

        .payment-option.selected .check-mark {
            display: flex;
            animation: checkPop 0.3s ease;
        }

        @keyframes checkPop {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .payment-option {
                padding: 10px 8px;
            }

            .payment-icon {
                width: 32px;
                height: 32px;
                font-size: 16px;
            }

            .payment-label {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .payment-methods {
                grid-template-columns: 1fr;
            }

            .payment-option {
                padding: 10px 12px;
            }
        }
    </style>
                   <div class="container" style="max-width: 600px; margin: 0 auto;">
        <div class="payment-section">
            <div class="payment-methods">
                <!-- Cash on Delivery -->
                <div class="payment-option p_cash selected" onclick="selectPayment(this)">
                    <input type="radio" name="payment_method" id="cash" value="cash" checked required>
                    <div class="payment-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <label class="payment-label" for="cash">
                        ক্যাশ অন ডেলিভারি
                    </label>
                    <div class="check-mark">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
@if($bkash_gateway)
                <!-- Bkash -->
                <div class="payment-option p_bkash" onclick="selectPayment(this)">
                    <input type="radio" name="payment_method" id="bkash" value="bkash" required>
                    <div class="payment-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <label class="payment-label" for="bkash">
                        বিকাশ
                    </label>
                    <div class="check-mark">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
@endif

   @if($shurjopay_gateway)
                <!-- Shurjopay -->
                <div class="payment-option p_shurjo" onclick="selectPayment(this)">
                    <input type="radio" name="payment_method" id="shurjopay" value="shurjopay" required>
                    <div class="payment-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <label class="payment-label" for="shurjopay">
                        সূর্যপে
                    </label>
                    <div class="check-mark">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
@endif
   @if($paystation_gateway)
                <!-- Paystation --> 
                <div class="payment-option p_paystation" onclick="selectPayment(this)">
                    <input type="radio" name="payment_method" id="paystation" value="paystation" required>
                    <div class="payment-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <label class="payment-label" for="paystation">
                        পেস্টেশন
                    </label>
                    <div class="check-mark">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>


    <script>
        function selectPayment(element) {
            // Remove selected from all
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected to clicked
            element.classList.add('selected');
            
            // Check radio
            const radio = element.querySelector('input[type="radio"]');
            radio.checked = true;
        }

        // Handle radio changes
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                selectPayment(this.closest('.payment-option'));
            });
        });
    </script>