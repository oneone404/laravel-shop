{{-- resources/views/admin/add-card.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Thêm Thẻ Game')

@section('content')
<style>
/* ===== Layout polish ===== */
.page-wrap {max-width: 1250px; margin-inline: auto; margin-top: 60px;}
.card { border: 1px solid rgba(0,0,0,.06); border-radius: 16px; overflow: hidden; }
.card-header { background: linear-gradient(180deg,#f8fafc,#f1f5f9); }
.btn-round { border-radius: 999px; }
.shadow-soft { box-shadow: 0 6px 20px rgba(2,6,23,.06); }

/* ===== Terminal container ===== */
.terminal {
    background: #0b1220;
    color: #d1d5db;
    border-radius: 14px;
    border: 1px solid #1e293b;
    overflow: hidden;
    margin: 0 12px;  
}
.terminal__topbar {
    background: #0f172a;
    border-bottom: 1px solid #1e293b;
    padding: 8px 12px;
    display: flex; align-items: center; gap: 8px;
}
.dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.dot--red { background: #ff5f56; }
.dot--yellow { background: #ffbd2e; }
.dot--green { background: #27c93f; }
.terminal__title { color: #94a3b8; font-size: 12px; margin-left: 6px; }

/* ===== Terminal body as 3-column list ===== */
.log {
    --row-h: 40px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 13px;
}
.log__head {
    display: grid; grid-template-columns: 110px 1fr 1fr; gap: 12px;
    align-items: center;
    padding: 10px 14px;
    color: #e2e8f0;
    background: linear-gradient(180deg, rgba(148,163,184,.12), rgba(148,163,184,.06));
    position: sticky; top: 0; z-index: 5;
    border-bottom: 1px solid #1e293b;
}
.log__body {
    max-height: 460px; overflow: auto;
    scroll-behavior: smooth;
}
.log__row {
    display: grid; grid-template-columns: 110px 1fr 1fr; gap: 12px;
    align-items: center;
    min-height: var(--row-h);
    padding: 8px 14px;
    border-bottom: 1px dashed rgba(148,163,184,.12);
}
.log__row:hover { background: rgba(148,163,184,.06); }
.log__gutter { color:#94a3b8; display:flex; align-items:center; gap:8px;}
.gutter-bar { width: 3px; height: 18px; border-radius: 3px; background: #334155; opacity:.9; }
.log__cell { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.badge-status { font-size: 12px; padding: 3px 8px; border-radius: 999px; border: 1px solid transparent; }
.badge--ok    { color:#16a34a; background: rgba(34,197,94,.12); border-color: rgba(34,197,94,.35); }
.badge--fail  { color:#ef4444; background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.35); }
.badge--warn  { color:#f59e0b; background: rgba(245,158,11,.12); border-color: rgba(245,158,11,.35); }
.badge--muted { color:#94a3b8; background: rgba(148,163,184,.12); border-color: rgba(148,163,184,.35); }

/* utilities */
.kbd{font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;background:#0f172a;color:#e5e7eb;border:1px solid #1e293b;border-bottom-width:2px;padding:2px 6px;border-radius:6px;font-size:12px;}
.helpline { color:#64748b; margin-top: 20px;}
textarea.form-control { font-family: Consolas, Menlo, Monaco, "Liberation Mono", monospace; }
</style>

<div class="page-wrap py-4">

    @if ($errors->any())
        <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
        {{-- LEFT: FORM --}}
        <div class="col-lg-5">
            <div class="card shadow-soft">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small text-muted">FORMAT: <code>Số Seri: SERIAL - Mã Nạp: PASSWORD</code></div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="cardForm" method="POST" action="{{ route('admin.cards.store') }}">
                        @csrf
                        <textarea name="cards" class="form-control" rows="12" placeholder="Ví Dụ:
Số Seri: ZC001 - Mã Nạp: ABC123
Số Seri: ZC002 - Mã Nạp: XYZ789">{{ old('cards', session('old_cards', '')) }}</textarea>
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <button type="submit" class="btn btn-success btn-round" id="btnSend">🚀 Send</button>
                            <button type="button" class="btn btn-outline-dark btn-round" id="btnClearInput">🧹 Clear</button>
                            <button type="button" class="btn btn-outline-dark btn-round" id="btnCheckCount">🔍 Check</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT: TERMINAL --}}
        <div class="col-lg-7">
            <div class="card shadow-soft">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><strong class="fw-semibold">Terminal</strrong></span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm btn-round" id="btnCopy">📄 Copy</button>
                        <button class="btn btn-outline-secondary btn-sm btn-round" id="btnDownload">⬇️ Log</button>
                        <button class="btn btn-outline-danger btn-sm btn-round" id="btnClearTerminal">🗑️ Clear</button>
                    </div>
                </div>

                <div class="terminal">
                    <div class="terminal__topbar">
                        <span class="dot dot--red"></span>
                        <span class="dot dot--yellow"></span>
                        <span class="dot dot--green"></span>
                        <span class="terminal__title">bash — add-card@server</span >
                    </div>

                    {{-- Header columns --}}
                    <div class="log">
                        <div class="log__head">
                            <div class="log__gutter"><div class="gutter-bar"></div> STATUS</div>
                            <div>SERIAL</div>
                            <div>PASSWORD</div>
                        </div>

                        <div id="terminalBody" class="log__body" aria-live="polite">
                            @php
                                $lines = session('results', []);
                                // Helper to parse "SERIAL | PASSWORD | STATUS"
                                function parseLine($line) {
                                    $parts = array_map('trim', explode('|', $line));
                                    // try to map by position regardless of order
                                    $serial   = $parts[0] ?? '';
                                    $password = $parts[1] ?? '';
                                    $status   = strtoupper($parts[2] ?? '');
                                    return [$serial, $password, $status];
                                }
                            @endphp

                            @if(!empty($lines))
                                @foreach ($lines as $line)
                                    @php [$serial, $password, $status] = parseLine($line); @endphp
                                    <div class="log__row">
                                        <div class="log__gutter">
                                            <div class="gutter-bar"></div>
                                            @if($status === 'SUCCESS')
                                                <span class="badge-status badge--ok">SUCCESS</span>
                                            @elseif($status === 'FALSE')
                                                <span class="badge-status badge--fail">FALSE</span>
                                            @elseif(Str::startsWith($status, 'ERROR'))
                                                <span class="badge-status badge--warn">ERROR</span>
                                            @else
                                                <span class="badge-status badge--muted">{{ $status ?: '...' }}</span>
                                            @endif
                                        </div>
                                        <div class="log__cell">{{ $serial }}</div>
                                        <div class="log__cell">{{ $password }}</div>
                                    </div>
                                @endforeach
                            @else
                                <div class="log__row">
                                    <div class="log__gutter"><div class="gutter-bar"></div><span class="badge-status badge--muted">INFO</span></div>
                                    <div class="log__cell text-muted">Chưa Có Dữ Liệu</div>
                                    <div class="log__cell"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-3 py-2 helpline small">
                    <span class="badge-status badge--ok">SUCCESS</span> : <span class="badge-status badge--fail">FALSE</span> : <span class="badge-status badge--warn">WARNING</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS helpers --}}
<script>
(function(){
    const term = document.getElementById('terminalBody');
    const btnCheck = document.getElementById('btnCheckCount');
    const spanCount = document.getElementById('countResult');
    const btnSend = document.getElementById('btnSend');
    const form = document.getElementById('cardForm');
    const btnClearInput = document.getElementById('btnClearInput');
    const btnCopy = document.getElementById('btnCopy');
    const btnDownload = document.getElementById('btnDownload');
    const btnClearTerminal = document.getElementById('btnClearTerminal');

    // Helper: scroll bottom
    function scrollTerm(){ term.scrollTop = term.scrollHeight; }
    scrollTerm();

    // Append a row (status, serial, pass)
    function appendRow(status, serial='', password='') {
        const row = document.createElement('div');
        row.className = 'log__row';
        const badgeClass =
            status === 'SUCCESS' ? 'badge--ok' :
            status === 'FALSE'   ? 'badge--fail' :
            status.startsWith('ERROR') ? 'badge--warn' : 'badge--muted';

        row.innerHTML = `
            <div class="log__gutter">
                <div class="gutter-bar"></div>
                <span class="badge-status ${badgeClass}">${status}</span>
            </div>
            <div class="log__cell">${escapeHtml(serial)}</div>
            <div class="log__cell">${escapeHtml(password)}</div>
        `;
        term.appendChild(row);
        scrollTerm();
    }

    // Check count
    btnCheck?.addEventListener('click', async () => {
        try {
            const resp = await fetch("{{ route('admin.cards.check') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const data = await resp.json();
            if (data.ok) {
                appendRow('INFO', 'Số Thẻ Còn Lại', String(data.count));
            } else {
                appendRow('ERROR', '', data.message ?? 'Không lấy được số thẻ.');
            }
        } catch (e) {
            appendRow('ERROR', '', 'fetch failed');
        }
    });

    // Disable button on submit
    form.addEventListener('submit', () => {
        btnSend.disabled = true;
        btnSend.textContent = '⏳ Sending...';
    });

    // Clear input
    btnClearInput.addEventListener('click', () => {
        form.querySelector('textarea[name="cards"]').value = '';
        form.querySelector('textarea[name="cards"]').focus();
    });

    // Copy terminal
    btnCopy.addEventListener('click', async () => {
        try { await navigator.clipboard.writeText(tableToText()); } catch {}
    });

    // Download terminal as txt
    btnDownload.addEventListener('click', () => {
        const blob = new Blob([tableToText()], {type: 'text/plain;charset=utf-8'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = 'add-card-output.txt';
        document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
    });

    // Clear terminal
    btnClearTerminal.addEventListener('click', () => {
        term.innerHTML = '';
        appendRow('INFO', 'log', 'cleared');
    });

    function tableToText() {
        const rows = term.querySelectorAll('.log__row');
        const lines = [];
        rows.forEach(r => {
            const cols = r.querySelectorAll('.log__cell');
            const badge = r.querySelector('.badge-status')?.textContent || '';
            const serial = cols[0]?.textContent.trim() || '';
            const pass = cols[1]?.textContent.trim() || '';
            lines.push([serial, pass, badge].filter(Boolean).join(' | '));
        });
        return lines.join('\n');
    }
    function escapeHtml(s){
        return s.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }
})();
</script>
@endsection
