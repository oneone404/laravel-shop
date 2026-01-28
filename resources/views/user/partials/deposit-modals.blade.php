@if (Auth::check())
    <!-- Deposit Choice Modal -->
    <div class="balance-modal" id="balanceModal1">
        <div class="balance-overlay"></div>
        <div class="balance-body">
            <div class="balance-content">
                <h3 class="modal-title">Chọn Phương Thức</h3>
                <div class="modal-grid">
                    <button id="balancePayCard" class="balance-btn">
                        <i class="fa-solid fa-mobile-screen"></i>
                        <span>Nạp Thẻ Cào</span>
                    </button>
                    <button id="balancePayBank" class="balance-btn">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>Nạp Ngân Hàng</span>
                    </button>
                </div>
                <button class="modal-close-btn" onclick="closeModal('balanceModal1')">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Bank Transfer Details Modal -->
    <div class="balance-modal" id="balanceModal2">
        <div class="balance-overlay"></div>
        <div class="balance-body">
            <div class="balance-content">
                <div class="title-underline"></div>
                <h3 class="modal-title" style="color: var(--primary-color)">THÔNG TIN NẠP TIỀN</h3>

                <div class="transfer-card">
                    <div class="bank-info">
                        <img src="{{ asset('images/acbank.png') }}" alt="ACB">
                        <strong>{{ $bankCode ?? 'ACB' }}</strong>
                    </div>

                    <div class="info-row">
                        <span>Chủ tài khoản:</span>
                        <strong>{{ $accountName ?? 'N/A' }}</strong>
                    </div>

                    <div class="info-row">
                        <span>Số tài khoản:</span>
                        <div class="copy-box">
                            <strong id="stk">{{ $accountNumber ?? 'N/A' }}</strong>
                            <button class="copy-btn" onclick="copyText('stk', this)"><i
                                    class="fa-solid fa-copy"></i></button>
                        </div>
                    </div>

                    <div class="info-row">
                        <span>Nội dung:</span>
                        <div class="copy-box">
                            <strong id="noidung">ONEDZ{{ Auth::user()->id }}</strong>
                            <button class="copy-btn" onclick="copyText('noidung', this)"><i
                                    class="fa-solid fa-copy"></i></button>
                        </div>
                    </div>
                </div>

                <div class="qr-container">
                    <img id="qrImage"
                        src="https://qr.sepay.vn/img?bank={{ $bankCode ?? 'ACB' }}&acc={{ $accountNumber ?? '' }}&template=&des=ONEDZ{{ Auth::id() }}"
                        alt="QR Code">
                    <p>Quét mã để nạp tiền nhanh</p>
                </div>

                <button class="balance-btn" style="margin-top:20px;" onclick="closeModal('balanceModal2')">ĐÃ XỬ LÝ</button>
            </div>
        </div>
    </div>
@else
    <div class="balance-modal" id="balanceModal1">
        <div class="balance-overlay"></div>
        <div class="balance-body">
            <div class="balance-content">
                <h3 class="modal-title">Vui lòng đăng nhập</h3>
                <p style="text-align: center; margin-bottom: 20px;">Bạn cần đăng nhập để sử dụng tính năng nạp tiền.</p>
                <a href="{{ route('login') }}" class="btn btn--primary" style="width: 100%;">ĐĂNG NHẬP NGAY</a>
                <button class="modal-close-btn" onclick="closeModal('balanceModal1')">Đóng</button>
            </div>
        </div>
    </div>
@endif

<style>
    /* Modern Modal Styles */
    .balance-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        inset: 0;
        justify-content: center;
        align-items: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .balance-modal.show {
        display: flex;
        opacity: 1;
    }

    .balance-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .balance-body {
        position: relative;
        width: 100%;
        max-width: 450px;
        transform: translateY(20px);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .balance-modal.show .balance-body {
        transform: translateY(0);
    }

    .balance-content {
        background: var(--bg-card);
        padding: 30px;
        border-radius: 20px;
        border: 1px solid var(--hero-card-border);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .modal-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 25px;
        text-transform: uppercase;
    }

    .modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .balance-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 20px;
        background: var(--hero-card-bg);
        border: 1px solid var(--hero-card-border);
        border-radius: 15px;
        color: var(--text-color);
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .balance-btn i {
        font-size: 2.4rem;
        color: var(--primary-color);
    }

    .balance-btn:hover {
        background: var(--hero-card-border);
        transform: translateY(-3px);
        border-color: var(--primary-color);
    }

    .transfer-card {
        background: var(--hero-card-bg);
        border-radius: 15px;
        padding: 20px;
        border: 1px solid var(--hero-card-border);
        margin-bottom: 20px;
    }

    .bank-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--hero-card-border);
    }

    .bank-info img {
        height: 30px;
        border-radius: 5px;
    }

    .info-row {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 15px;
    }

    .info-row span {
        font-size: 1.2rem;
        opacity: 0.6;
    }

    .copy-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(0, 0, 0, 0.1);
        padding: 10px 15px;
        border-radius: 10px;
    }

    .copy-btn {
        background: var(--primary-color);
        color: #fff;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        cursor: pointer;
    }

    .qr-container {
        text-align: center;
        padding: 15px;
        background: #fff;
        border-radius: 15px;
        margin-bottom: 20px;
    }

    .qr-container img {
        max-width: 180px;
    }

    .qr-container p {
        color: #000;
        font-size: 1.2rem;
        margin-top: 10px;
        font-weight: 600;
    }

    .modal-close-btn {
        width: 100%;
        background: transparent;
        border: none;
        color: var(--text-light);
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
    }

    .title-underline {
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--second-color));
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>

<script>
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    function copyText(id, btn) {
        const text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i>';
            setTimeout(() => btn.innerHTML = originalHtml, 2000);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const btnDeposit = document.getElementById('btnDeposit');
        const pcBtnDeposit = document.getElementById('pcBtnDeposit');
        const heroBtnDeposit = document.getElementById('heroBtnDeposit');
        const linkDeposit = document.getElementById('linkDeposit');
        const modal1 = document.getElementById('balanceModal1');
        const modal2 = document.getElementById('balanceModal2');

        const openDeposit = () => modal1.classList.add('show');

        if (btnDeposit) btnDeposit.addEventListener('click', openDeposit);
        if (pcBtnDeposit) pcBtnDeposit.addEventListener('click', openDeposit);
        if (heroBtnDeposit) heroBtnDeposit.addEventListener('click', (e) => { e.preventDefault(); openDeposit(); });
        if (linkDeposit) linkDeposit.addEventListener('click', (e) => { e.preventDefault(); openDeposit(); });

        document.getElementById('balancePayBank')?.addEventListener('click', () => {
            modal1.classList.remove('show');
            modal2.classList.add('show');
        });

        document.querySelectorAll('.balance-overlay').forEach(el => {
            el.addEventListener('click', () => {
                modal1.classList.remove('show');
                modal2.classList.add('show');
                if (modal2) modal2.classList.remove('show');
            });
        });
    });
</script>
