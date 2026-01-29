<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini Chatbot Test Results</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .test-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }
        
        .test-card:hover {
            transform: translateY(-5px);
        }
        
        .test-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .test-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .test-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }
        
        .question-section {
            margin-bottom: 20px;
        }
        
        .label {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .question {
            background: #f8f9ff;
            padding: 15px 20px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            font-size: 16px;
            color: #333;
        }
        
        .response {
            background: #f0fdf4;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #10b981;
            font-size: 16px;
            line-height: 1.6;
            color: #1f2937;
            white-space: pre-wrap;
        }
        
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .status.success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status.error {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .summary {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        
        .summary h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .summary-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        .emoji {
            font-size: 3rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🤖 Gemini AI Chatbot Test Results</h1>
        
        <div class="summary">
            <div class="emoji">✅</div>
            <h2>Test Summary</h2>
            <div class="summary-stats">
                <div class="stat">
                    <div class="stat-value">{{ count($results) }}</div>
                    <div class="stat-label">Total Tests</div>
                </div>
                <div class="stat">
                    <div class="stat-value">{{ count(array_filter($results, fn($r) => !str_contains($r['response'], 'Lỗi') && !str_contains($r['response'], 'lỗi'))) }}</div>
                    <div class="stat-label">Successful</div>
                </div>
            </div>
        </div>
        
        @foreach($results as $index => $result)
        <div class="test-card">
            <div class="test-header">
                <span class="test-badge">Test {{ $index + 1 }}</span>
                <span class="test-name">{{ $result['test'] }}</span>
            </div>
            
            <div class="question-section">
                <div class="label">👤 User Question</div>
                <div class="question">{{ $result['question'] }}</div>
            </div>
            
            <div class="question-section">
                <div class="label">🤖 AI Response</div>
                <div class="response">{{ $result['response'] }}</div>
                
                @if(str_contains($result['response'], 'Lỗi') || str_contains($result['response'], 'lỗi'))
                    <span class="status error">❌ Error Detected</span>
                @else
                    <span class="status success">✅ Success</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>
