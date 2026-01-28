@extends('layouts.user.app')

@section('title', 'Nhập Code')

@section('content')

    <x-hero-header title="HỆ THỐNG NHẬP CODE"
        description="Nhập mã quà tặng của bạn để nhận phần thưởng hấp dẫn ngay lập tức." />

    <div class="gift-code-container">
        <div class="gift-card">
            <h4 class="text-center"><i class="fas fa-gift"></i> NHẬP CODE HÀNG LOẠT</h4>

            <form id="gift-code-form">
                <div class="import-row">
                    <div class="form-group">
                        <label for="roleIds" class="form-label">Danh sách ID Game:</label>
                        <textarea class="glass-textarea" id="roleIds" name="roleIds"
                            placeholder="Mỗi ID một dòng&#10;Định dạng: xxxx-xxxx-xxxx" required></textarea>
                        <p class="helper-text">* Định dạng bắt buộc: xxxx-xxxx-xxxx</p>
                    </div>

                    <div class="form-group">
                        <label for="codes" class="form-label">Danh sách Mã Code:</label>
                        <textarea class="glass-textarea" id="codes" name="codes" placeholder="Mỗi mã một dòng"
                            required></textarea>
                        <p class="helper-text">* Mỗi mã tối thiểu 6 ký tự</p>
                    </div>
                </div>

                <div id="validation-error" class="alert alert-danger" style="display: none; margin-bottom: 20px;"></div>

                <button type="submit" class="btn btn--primary btn-submit" id="submitBtn">
                    BẮT ĐẦU XỬ LÝ <i class="fas fa-rocket ml-2"></i>
                </button>
            </form>

            <div id="bulk-results" class="bulk-results"></div>
        </div>
    </div>

    <script>
        document.getElementById('gift-code-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const roleIdsRaw = document.getElementById('roleIds').value.trim();
            const codesRaw = document.getElementById('codes').value.trim();
            const resultContainer = document.getElementById('bulk-results');
            const errorDiv = document.getElementById('validation-error');
            const submitBtn = document.getElementById('submitBtn');

            errorDiv.style.display = 'none';
            resultContainer.innerHTML = '';

            // 1. Parse and Validate
            const roleIds = roleIdsRaw.split('\n').map(s => s.trim()).filter(s => s !== '');
            const codes = codesRaw.split('\n').map(s => s.trim()).filter(s => s !== '');

            if (roleIds.length === 0 || codes.length === 0) {
                errorDiv.innerText = '⚠️ Vui lòng nhập ít nhất một ID và một mã code!';
                errorDiv.style.display = 'block';
                return;
            }

            // Game ID Regex: xxxx-xxxx-xxxx (letters and numbers)
            const idRegex = /^[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}$/;
            const invalidIds = roleIds.filter(id => !idRegex.test(id));
            if (invalidIds.length > 0) {
                errorDiv.innerText = `⚠️ ID sau không đúng định dạng (xxxx-xxxx-xxxx): ${invalidIds[0]}...`;
                errorDiv.style.display = 'block';
                return;
            }

            const invalidCodes = codes.filter(c => c.length < 6);
            if (invalidCodes.length > 0) {
                errorDiv.innerText = `⚠️ Mã code phải từ 6 ký tự trở lên: ${invalidCodes[0]}...`;
                errorDiv.style.display = 'block';
                return;
            }

            // 2. Start Processing
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';

            // Create initial status list
            const queue = [];
            roleIds.forEach(id => {
                codes.forEach(code => {
                    const idSafe = id.replace(/-/g, '_'); // For element ID
                    const resultId = `res-${idSafe}-${code}`;
                    const item = document.createElement('div');
                    item.className = 'result-item';
                    item.id = resultId;
                    item.innerHTML = `
                        <span class="result-id"><i class="fas fa-user-circle"></i> ${id}</span>
                        <span class="result-code">${code}</span>
                        <span class="result-status status-pending">Đang chờ...</span>
                    `;
                    resultContainer.appendChild(item);
                    queue.push({ id, code, resultId });
                });
            });

            // 3. Process each pair
            for (const task of queue) {
                const statusEl = document.querySelector(`#${task.resultId} .result-status`);
                statusEl.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang gửi...';

                try {
                    const response = await fetch('https://accone.vn/api/nap-zing/gift-code', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ roleId: task.id, code: task.code })
                    });
                    const data = await response.json();

                    if (data.status) {
                        statusEl.className = 'result-status status-success';
                        statusEl.innerHTML = '<i class="fas fa-check-circle"></i> Thành công';
                    } else {
                        statusEl.className = 'result-status status-error';
                        statusEl.innerHTML = `<i class="fas fa-times-circle"></i> ${data.message || 'Thất bại'}`;
                    }
                } catch (error) {
                    statusEl.className = 'result-status status-error';
                    statusEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Lỗi kết nối';
                }

                // Add small delay to avoid rate limit
                await new Promise(r => setTimeout(r, 300));
            }

            submitBtn.disabled = false;
            submitBtn.innerHTML = 'BẮT ĐẦU XỬ LÝ <i class="fas fa-rocket ml-2"></i>';
        });
    </script>

@endsection