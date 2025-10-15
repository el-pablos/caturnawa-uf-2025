<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winner Certificate</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            width: 297mm;
            height: 210mm;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .certificate-container {
            width: 100%;
            height: 100%;
            padding: 30mm;
            position: relative;
        }
        
        .certificate-border {
            width: 100%;
            height: 100%;
            border: 8px solid #FFD700;
            border-radius: 20px;
            background: white;
            padding: 20mm;
            position: relative;
            box-shadow: inset 0 0 30px rgba(102, 126, 234, 0.1);
        }
        
        .inner-border {
            width: 100%;
            height: 100%;
            border: 2px solid #667eea;
            border-radius: 15px;
            padding: 15mm;
            position: relative;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15mm;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }
        
        .title {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 8px;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 18px;
            color: #764ba2;
            font-style: italic;
            letter-spacing: 2px;
        }
        
        .content {
            text-align: center;
            margin-top: 10mm;
        }
        
        .award-text {
            font-size: 16px;
            color: #333;
            margin-bottom: 8mm;
            line-height: 1.6;
        }
        
        .recipient-name {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 15px 0;
            text-transform: uppercase;
            border-bottom: 3px solid #FFD700;
            display: inline-block;
            padding: 10px 40px;
        }
        
        .team-name {
            font-size: 24px;
            color: #764ba2;
            margin: 10px 0;
            font-style: italic;
        }
        
        .achievement {
            font-size: 28px;
            font-weight: bold;
            color: #FFD700;
            margin: 15px 0;
            text-transform: uppercase;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .competition-name {
            font-size: 20px;
            color: #333;
            margin: 10px 0;
            font-weight: 600;
        }
        
        .footer {
            position: absolute;
            bottom: 15mm;
            left: 20mm;
            right: 20mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .signature-block {
            text-align: center;
            width: 40%;
        }
        
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 50px;
            padding-top: 10px;
            font-size: 14px;
            color: #333;
            font-weight: 600;
        }
        
        .signature-title {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .certificate-number {
            position: absolute;
            bottom: 5mm;
            left: 20mm;
            font-size: 10px;
            color: #999;
        }
        
        .date {
            position: absolute;
            bottom: 5mm;
            right: 20mm;
            font-size: 12px;
            color: #666;
        }
        
        .decorative-element {
            position: absolute;
            width: 100px;
            height: 100px;
            opacity: 0.1;
        }
        
        .decorative-top-left {
            top: 10mm;
            left: 10mm;
            background: radial-gradient(circle, #667eea 0%, transparent 70%);
        }
        
        .decorative-bottom-right {
            bottom: 10mm;
            right: 10mm;
            background: radial-gradient(circle, #764ba2 0%, transparent 70%);
        }
        
        .medal-icon {
            font-size: 60px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="inner-border">
                <div class="decorative-element decorative-top-left"></div>
                <div class="decorative-element decorative-bottom-right"></div>
                
                <div class="header">
                    <div class="title">CERTIFICATE</div>
                    <div class="subtitle">of Achievement</div>
                </div>
                
                <div class="content">
                    <div class="award-text">
                        This certificate is proudly presented to
                    </div>
                    
                    <div class="recipient-name">{{ $participant_name }}</div>
                    
                    @if($is_team && $team_name)
                    <div class="team-name">{{ $team_name }}</div>
                    @endif
                    
                    <div class="medal-icon">🏆</div>
                    
                    <div class="achievement">{{ $award_title }}</div>
                    
                    <div class="award-text">
                        in the
                    </div>
                    
                    <div class="competition-name">{{ $competition_name }}</div>
                    <div class="competition-name" style="font-size: 16px; color: #666;">
                        ({{ $competition_type }})
                    </div>
                    
                    <div class="award-text" style="margin-top: 15px;">
                        CATURNAWA UNAS FEST 2025
                    </div>
                </div>
                
                <div class="footer">
                    <div class="signature-block">
                        <div class="signature-line">
                            Director of CATURNAWA
                        </div>
                        <div class="signature-title">Event Organizer</div>
                    </div>
                    
                    <div class="signature-block">
                        <div class="signature-line">
                            Head of Competition
                        </div>
                        <div class="signature-title">Competition Committee</div>
                    </div>
                </div>
                
                <div class="certificate-number">
                    Certificate No: {{ $certificate_number }}
                </div>
                
                <div class="date">
                    {{ $date }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>

