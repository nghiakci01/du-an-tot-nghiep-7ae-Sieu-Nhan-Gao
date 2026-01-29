<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Chatbot Test</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .question {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .response {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            white-space: pre-wrap;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }
        .success { background: #d1fae5; color: #065f46; }
        .error { background: #fee2e2; color: #991b1b; }
        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Quick Chatbot Test</h1>
        
        <div id="loading" class="loading">
            Testing chatbot... Please wait...
        </div>
        
        <div id="results" style="display: none;"></div>
    </div>

    <script>
        async function testChatbot() {
            const tests = [
                'Xin chào!',
                'Có sản phẩm laptop không?',
                'Giá iPhone bao nhiêu?'
            ];
            
            const resultsDiv = document.getElementById('results');
            const loadingDiv = document.getElementById('loading');
            
            for (let i = 0; i < tests.length; i++) {
                const question = tests[i];
                
                try {
                    const response = await fetch('/api/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: question })
                    });
                    
                    const data = await response.json();
                    
                    const testDiv = document.createElement('div');
                    testDiv.className = 'test-section';
                    testDiv.innerHTML = `
                        <div class="question">👤 ${question}</div>
                        <div class="response">🤖 ${data.reply || 'No response'}</div>
                        <span class="status ${data.reply ? 'success' : 'error'}">
                            ${data.reply ? '✅ Success' : '❌ Failed'}
                        </span>
                    `;
                    
                    resultsDiv.appendChild(testDiv);
                    
                } catch (error) {
                    const testDiv = document.createElement('div');
                    testDiv.className = 'test-section';
                    testDiv.innerHTML = `
                        <div class="question">👤 ${question}</div>
                        <div class="response">❌ Error: ${error.message}</div>
                        <span class="status error">❌ Failed</span>
                    `;
                    resultsDiv.appendChild(testDiv);
                }
            }
            
            loadingDiv.style.display = 'none';
            resultsDiv.style.display = 'block';
        }
        
        // Run tests on page load
        testChatbot();
    </script>
</body>
</html>
