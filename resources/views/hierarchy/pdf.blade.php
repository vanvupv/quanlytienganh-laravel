<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Xuất PDF: {{ $tree->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #334155;
            line-height: 1.5;
            padding: 10px;
        }
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0 0 8px 0;
        }
        .meta {
            font-size: 11px;
            color: #64748b;
        }
        .description {
            background-color: #f8fafc;
            border-left: 4px solid #cbd5e1;
            padding: 10px 15px;
            margin: 15px 0;
            font-style: italic;
            font-size: 12px;
        }
        .tree-container {
            background-color: #fafafa;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            white-space: pre;
            line-height: 1.7;
            color: #0f172a;
        }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">🌳 Sơ Đồ Cấu Trúc Cây Phân Cấp</h1>
        <div class="meta">
            <strong>Tên cây:</strong> {{ $tree->name }} &nbsp;|&nbsp; 
            <strong>Mã Code:</strong> <code>{{ $tree->code }}</code> &nbsp;|&nbsp; 
            <strong>Phân hệ:</strong> {{ strtoupper($tree->module) }}
        </div>
    </div>

    @if($tree->description)
        <div class="description">
            {{ $tree->description }}
        </div>
    @endif
    
    <div class="tree-container">{!! $textTree !!}</div>
    
    <div class="footer">
        Được xuất bản tự động bởi Hệ thống Quản lý Tiếng Anh lúc {{ now()->format('H:i - d/m/Y') }}
    </div>
</body>
</html>
