<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        h2 {
            color: #34495e;
            margin-top: 40px;
            margin-bottom: 20px;
            font-size: 2em;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }

        h3 {
            color: #2980b9;
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.5em;
        }

        h4 {
            color: #16a085;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        p {
            margin-bottom: 15px;
            color: #555;
        }

        ul, ol {
            margin-left: 30px;
            margin-bottom: 15px;
        }

        li {
            margin-bottom: 8px;
            color: #555;
        }

        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', Courier, monospace;
            color: #e74c3c;
            font-size: 0.9em;
        }

        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 5px;
            overflow-x: auto;
            margin-bottom: 20px;
        }

        pre code {
            background: none;
            color: #ecf0f1;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            font-size: 0.95em;
        }

        table thead {
            background: #3498db;
            color: white;
        }

        table th {
            padding: 14px 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: 600;
            white-space: nowrap;
        }

        table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        table tbody tr:hover {
            background: #e8f4f8;
            transition: background 0.2s ease;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        /* Style checkmarks and X marks in tables */
        table td:contains('✅'), table td:contains('❌') {
            text-align: center;
            font-size: 1.2em;
        }

        hr {
            border: none;
            border-top: 2px solid #ecf0f1;
            margin: 40px 0;
        }

        strong {
            color: #2c3e50;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .badge-success {
            background: #27ae60;
            color: white;
        }

        .badge-danger {
            background: #e74c3c;
            color: white;
        }

        blockquote {
            border-left: 4px solid #3498db;
            padding-left: 20px;
            margin: 20px 0;
            color: #555;
            font-style: italic;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        {!! $content !!}
    </div>
</body>
</html>
