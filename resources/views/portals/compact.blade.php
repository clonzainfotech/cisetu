
<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $village->name_local }} | Digital Property Tax Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&family=DM+Serif+Display:ital@0;1&family=Noto+Sans+Gujarati:wght@400;500;600;700&display=swap');
    *{box-sizing:border-box;margin:0;padding:0}
    :root{
      --ink:#0a0a0a;--ink2:#3d4460;--ink3:#7c849e;--ink4:#b0b4c1;
      --wa:#25D366;--wa2:#1aab52;--wal:#e8faf0;
      --bg:#fafaf8;--bg2:#f4f4f1;--white:#ffffff;--bdr:rgba(0,0,0,0.08);
      --serif:'DM Serif Display',Georgia,serif;--sans:'DM Sans',sans-serif;
      --gu:'Noto Sans Gujarati','DM Sans',sans-serif;
    }
    body{font-family:var(--sans);background:var(--bg);color:var(--ink);-webkit-font-smoothing:antialiased;font-size:15px}

    /* NAV */
    .nav{background:var(--bg);border-bottom:1px solid var(--bdr);padding:0 28px}
    .nav-i{max-width:1100px;margin:auto;height:60px;display:flex;align-items:center;justify-content:space-between;gap:1rem}
    .brand{display:flex;align-items:center;gap:10px;text-decoration:none}
    .logo-sq{width:34px;height:34px;border-radius:35px;border:1px solid var(--bdr);background:var(--bg2);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
    .logo-sq i{font-size:17px;color:var(--ink3)}
    .logo-sq img{width:100%;height:100%;object-fit:cover;}
    .brand-t{font-size:13.5px;font-weight:600;color:var(--ink);line-height:1.25;font-family:var(--gu)}
    .brand-s{font-size:10px;color:var(--ink4);font-weight:400;margin-top:1px}
    .nav-r{display:flex;align-items:center;gap:24px}
    .nav-r a{font-size:13px;font-weight:500;color:var(--ink3);text-decoration:none;transition:color .15s}
    .nav-r a:hover{color:var(--ink)}
    .nav-btn{display:flex;align-items:center;gap:6px;padding:8px 18px;background:var(--ink);color:var(--bg)!important;border-radius:6px;font-size:13px;font-weight:500;text-decoration:none}
    .nav-btn i{font-size:16px;color:var(--wa)}

    /* HERO */
    .hero{padding:72px 28px 80px;border-bottom:1px solid var(--bdr)}
    .hero-i{max-width:1100px;margin:auto;display:grid;grid-template-columns:1fr 380px;gap:60px;align-items:center}
    .eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:500;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:22px}
    .pulse{width:6px;height:6px;border-radius:50%;background:var(--wa);animation:p 1.8s infinite}
    @keyframes p{0%,100%{opacity:1}50%{opacity:.25}}
    .hero h1{font-family:var(--serif);font-size:clamp(2.4rem,3.8vw,3.5rem);font-weight:400;line-height:1.07;letter-spacing:-.025em;color:var(--ink);margin-bottom:18px}
    .hero h1 em{font-style:italic;color:var(--ink3)}
    .hero-sub{font-size:14px;color:var(--ink3);line-height:1.75;max-width:420px;margin-bottom:28px}
    .hero-btns{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px}
    .btn-wa{display:inline-flex;align-items:center;gap:7px;padding:11px 22px;background:var(--wa);color:#fff;border-radius:6px;font-size:13.5px;font-weight:500;text-decoration:none;transition:background .2s}
    .btn-wa:hover{background:var(--wa2)}
    .btn-wa i{font-size:18px}
    .btn-ol{display:inline-flex;align-items:center;gap:7px;padding:11px 22px;border:1px solid var(--bdr);color:var(--ink3);border-radius:6px;font-size:13.5px;font-weight:500;text-decoration:none;transition:all .2s}
    .btn-ol:hover{border-color:var(--ink);color:var(--ink)}
    .trust{display:flex;gap:18px;flex-wrap:wrap}
    .tr{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--ink4)}
    .tr i{font-size:14px;color:var(--ink)}

    /* PHONE */
    .phone-wrap{display:flex;justify-content:center}
    .pf{width:240px;background:#111;border-radius:34px;border:1px solid #2a2a2a;overflow:hidden}
    .pn{background:#111;height:24px;display:flex;align-items:center;justify-content:center}
    .pnb{width:72px;height:5px;background:#222;border-radius:3px}
    .wh-bar{background:#075E54;padding:11px 14px;display:flex;align-items:center;gap:9px}
    .wh-av{width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
    .wh-av i{font-size:15px;color:#fff}
    .wh-av img{width:100%;height:100%;object-fit:cover;}
    .wh-nm{font-size:12.5px;font-weight:600;color:#fff;font-family:var(--sans)}
    .wh-st{font-size:10px;color:rgba(255,255,255,.5);font-family:var(--sans)}
    .chat{background:#ece5dd;padding:13px 11px;display:flex;flex-direction:column;gap:7px;min-height:248px}
    .mo{align-self:flex-end;background:#dcf8c6;padding:7px 10px;border-radius:11px 11px 2px 11px;font-size:11px;color:#1a2e1a;max-width:80%;font-family:var(--sans)}
    .mi{align-self:flex-start;background:#fff;padding:9px 11px;border-radius:11px 11px 11px 2px;font-size:11px;color:#222;max-width:88%;font-family:var(--sans);line-height:1.45}
    .mi strong{display:block;font-size:12px;font-weight:600;color:#111;margin-bottom:4px}
    .mi-pay{display:block;margin-top:7px;padding:6px 11px;background:#0a0a0a;color:#fff;text-align:center;border-radius:5px;font-size:11px;font-weight:500;text-decoration:none;font-family:var(--sans)}
    .mr{align-self:flex-start;background:#fff;padding:8px 11px;border-radius:11px 11px 11px 2px;display:flex;align-items:center;gap:7px}
    .rc{width:24px;height:24px;border-radius:50%;background:#dcf8c6;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .rc i{font-size:12px;color:#075E54}
    .rt{font-size:11px;font-weight:600;color:#111;font-family:var(--sans)}
    .rs{font-size:10px;color:#888;font-family:var(--sans)}

    /* STATS */
    .stats{background:var(--white);border-bottom:1px solid var(--bdr)}
    .stats-i{max-width:1100px;margin:auto;display:grid;grid-template-columns:repeat(4,1fr);padding:0 28px}
    .sc{padding:26px 18px;border-right:1px solid var(--bdr)}
    .sc:last-child{border-right:none}
    .sn{font-family:var(--serif);font-size:2rem;font-weight:400;color:var(--ink);line-height:1}
    .sl{font-size:11px;color:var(--ink4);font-weight:400;text-transform:uppercase;letter-spacing:.1em;margin-top:5px}

    /* HOW */
    .sec{max-width:1100px;margin:auto;padding:64px 28px}
    .tag{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:500;color:var(--ink3);text-transform:uppercase;letter-spacing:.12em;margin-bottom:14px}
    .tag::before{content:'';display:block;width:14px;height:1px;background:var(--ink4)}
    .sh{font-family:var(--serif);font-size:clamp(1.7rem,2.8vw,2.4rem);font-weight:400;letter-spacing:-.02em;line-height:1.1;color:var(--ink);margin-bottom:10px}
    .ss{font-size:14px;color:var(--ink3);line-height:1.7;max-width:460px}
    .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--bdr);border:1px solid var(--bdr);border-radius:12px;overflow:hidden;margin-top:36px}
    .step{background:var(--white);padding:28px 24px}
    .sn2{font-family:var(--serif);font-size:2.5rem;font-weight:400;color:rgba(0,0,0,.07);line-height:1;margin-bottom:16px}
    .sico{width:38px;height:38px;border-radius:8px;border:1px solid var(--bdr);display:flex;align-items:center;justify-content:center;margin-bottom:14px}
    .sico i{font-size:18px;color:var(--ink)}
    .step h4{font-size:14px;font-weight:600;color:var(--ink);margin-bottom:7px}
    .step p{font-size:12.5px;color:var(--ink3);line-height:1.65}
    .cta-row{text-align:center;margin-top:28px}

    /* SERVICES */
    .svc-bg{background:var(--bg2);border-top:1px solid var(--bdr);border-bottom:1px solid var(--bdr)}
    .svc-lay{display:grid;grid-template-columns:1fr 2fr;gap:48px;align-items:start}
    .svc-sticky{position:sticky;top:72px}
    .svc-list{border:1px solid var(--bdr);border-radius:10px;overflow:hidden;background:var(--white);display:flex;flex-direction:column;gap:0}
    .si{padding:18px 20px;display:grid;grid-template-columns:36px 1fr auto;gap:14px;align-items:center;border-bottom:1px solid var(--bdr);transition:background .15s;cursor:pointer}
    .si:last-child{border-bottom:none}
    .si:hover{background:var(--bg)}
    .si-ico{width:36px;height:36px;border-radius:7px;border:1px solid var(--bdr);display:flex;align-items:center;justify-content:center;flex-shrink:0;background:var(--white)}
    .si-ico i{font-size:17px;color:var(--ink)}
    .si h4{font-size:13.5px;font-weight:600;color:var(--ink);margin-bottom:2px;font-family:var(--gu)}
    .si p{font-size:11.5px;color:var(--ink4);line-height:1.4}
    .badge-a{padding:3px 9px;border-radius:4px;font-size:11px;font-weight:500;background:var(--wal);color:var(--wa2);white-space:nowrap}
    .badge-s{padding:3px 9px;border-radius:4px;font-size:11px;font-weight:500;background:var(--bg2);color:var(--ink4);border:1px solid var(--bdr);white-space:nowrap}
    .iso-card{margin-top:20px;padding:16px;background:var(--white);border:1px solid var(--bdr);border-radius:10px}
    .iso-card-head{display:flex;align-items:center;gap:7px;margin-bottom:6px}
    .iso-card-head i{font-size:15px;color:var(--ink)}
    .iso-card-head span{font-size:12px;font-weight:600;color:var(--ink)}
    .iso-card p{font-size:12px;color:var(--ink4);line-height:1.55}

    /* INITIATIVE */
    .init{background:var(--ink)}
    .init-i{max-width:1100px;margin:auto;padding:72px 28px;display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center}
    .init .tag{color:rgba(255,255,255,.3)}
    .init .tag::before{background:rgba(255,255,255,.2)}
    .init .sh{color:var(--white)}
    .init-desc{font-size:13.5px;color:rgba(255,255,255,.4);line-height:1.75;margin-top:10px}
    .init-card{border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:20px}
    .ip{padding:13px 0;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:space-between}
    .ip:last-child{border-bottom:none;padding-bottom:0}
    .ip:first-child{padding-top:0}
    .ip-k{font-size:12.5px;color:rgba(255,255,255,.35)}
    .ip-v{font-size:12.5px;font-weight:500;color:var(--white)}

    /* FOOTER */
    footer{background:var(--white);border-top:1px solid var(--bdr);padding:52px 28px 24px}
    .fi{max-width:1100px;margin:auto}
    .ft{display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:36px;padding-bottom:36px;border-bottom:1px solid var(--bdr);margin-bottom:20px}
    .fb-row{display:flex;align-items:center;gap:10px;margin-bottom:14px}
    .fb-sq{width:34px;height:34px;border-radius:8px;border:1px solid var(--bdr);background:var(--bg2);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
    .fb-sq i{font-size:17px;color:var(--ink3)}
    .fb-sq img{width:100%;height:100%;object-fit:cover;}
    .fb-n{font-size:13.5px;font-weight:600;color:var(--ink);font-family:var(--gu)}
    .fb-s{font-size:10px;color:var(--ink4)}
    .fa{font-size:12.5px;color:var(--ink4);line-height:1.7;margin-bottom:14px;max-width:260px;font-family: 'DM Sans';}
    .soc{display:flex;gap:7px}
    .sl2{width:30px;height:30px;border-radius:6px;border:1px solid var(--bdr);display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--ink4);text-decoration:none;transition:all .2s}
    .sl2:hover{border-color:var(--ink);color:var(--ink)}
    .fc h5{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:var(--ink);margin-bottom:14px}
    .fc ul{list-style:none}
    .fc li{margin-bottom:9px}
    .fc a{font-size:12.5px;color:var(--ink4);text-decoration:none;transition:color .15s}
    .fc a:hover{color:var(--ink)}
    .cr{display:flex;align-items:flex-start;gap:8px;margin-bottom:10px;font-size:12.5px;color:var(--ink4)}
    .cr i{font-size:14px;color:var(--ink3);flex-shrink:0;margin-top:1px}
    .cr a{color:var(--ink4);text-decoration:none;transition:color .15s}
    .cr a:hover{color:var(--ink)}
    .fb2{display:flex;align-items:center;justify-content:space-between;font-size:11.5px;color:var(--ink4);flex-wrap:wrap;gap:8px}
    .pb{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border:1px solid var(--bdr);border-radius:20px;font-size:11px;font-weight:500;color:var(--ink4)}
    .pb strong{color:var(--ink);font-weight:600}

    @media(max-width:860px){
      .hero-i,.init-i{grid-template-columns:1fr}
      .phone-wrap{display:none}
      .svc-lay{grid-template-columns:1fr;gap:24px}
      .svc-sticky{position:static}
      .ft{grid-template-columns:1fr 1fr;gap:24px}
      .stats-i{grid-template-columns:repeat(2,1fr)}
      .sc{border-right:none;border-bottom:1px solid var(--bdr)}
      .sc:nth-child(odd){border-right:1px solid var(--bdr)}
    }
    @media(max-width:580px){
      .steps,.ft{grid-template-columns:1fr}
      nav .nav-r a:not(.nav-btn){display:none}
      .hero{padding:52px 20px 60px}
      .sec,.init-i{padding:52px 20px}
    }
    </style>
</head>
<body>

<!-- NAV -->
<nav class="nav">
  <div class="nav-i">
    <a href="#" class="brand">
      <div class="logo-sq">
        @if($village->logo)
            <img src="{{ Storage::url($village->logo) }}" alt="Logo" >
        @else
            <i class="ti ti-building-community" aria-hidden="true"></i>
        @endif
      </div>
      <div>
        <div class="brand-t">ગ્રામ પંચાયત — {{ $village->name_local }}</div>
        <div class="brand-s">Smart Village Portal</div>
      </div>
    </a>
    <div class="nav-r">
      <a href="#hero">Home</a>
      <a href="#how">How to Pay</a>
      <a href="#services">Services</a>
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="nav-btn"><i class="ti ti-brand-whatsapp"></i> Pay Tax</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-i">
    <div>
      <div class="eyebrow"><span class="pulse"></span> Official Digital Tax Portal</div>
      <h1 class="t-gu">તમારો પ્રોપર્ટી<br>વેરો <em>WhatsApp</em><br>થી ભરો.</h1>
      <p class="hero-sub">No more queues. {{ $village->name_local }} Gram Panchayat's automated WhatsApp system lets you view and pay property tax instantly — official digital receipt in seconds.</p>
      <div class="hero-btns">
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-wa"><i class="ti ti-brand-whatsapp"></i> Start on WhatsApp</a>
        <a href="#how" class="btn-ol"><i class="ti ti-player-play" style="font-size:14px"></i> How It Works</a>
      </div>
      <div class="trust">
        <span class="tr"><i class="ti ti-shield-check"></i> 100% Secure</span>
        <span class="tr"><i class="ti ti-bolt"></i> Instant Receipt</span>
        <span class="tr"><i class="ti ti-currency-rupee"></i> Zero Fee</span>
      </div>
    </div>
    <div class="phone-wrap">
      <div class="pf">
        <div class="pn"><div class="pnb"></div></div>
        <div class="wh-bar">
          <div class="wh-av">
            @if($village->logo)
                <img src="{{ Storage::url($village->logo) }}" alt="Logo" onerror="this.src='{{ asset('assets/images/logo.jpeg') }}'">
            @else
                <i class="ti ti-building-community" aria-hidden="true"></i>
            @endif
          </div>
          <div><div class="wh-nm">{{ $village->name_local }}</div><div class="wh-st">Gram Panchayat · Online</div></div>
        </div>
        <div class="chat">
          <div class="mo">Hi Panchayat</div>
          <div class="mi">
            <strong>Welcome, Citizen</strong>
            Your outstanding tax is <strong>₹1,250</strong>
            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="mi-pay">Pay Now via UPI →</a>
          </div>
          <div class="mr">
            <div class="rc"><i class="ti ti-check"></i></div>
            <div><div class="rt">Payment Successful</div><div class="rs">Receipt sent to WhatsApp</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<div class="stats">
  <div class="stats-i">
    <div class="sc"><div class="sn">2,500+</div><div class="sl">Homes Connected</div></div>
    <div class="sc"><div class="sn">24/7</div><div class="sl">System Uptime</div></div>
    <div class="sc"><div class="sn">0%</div><div class="sl">Convenience Fee</div></div>
    <div class="sc"><div class="sn">100%</div><div class="sl">Digital Accuracy</div></div>
  </div>
</div>

<!-- HOW IT WORKS -->
<div style="background:var(--bg2);border-top:1px solid var(--bdr);border-bottom:1px solid var(--bdr)" id="how">
<div class="sec">
  <div class="tag">Process</div>
  <h2 class="sh t-gu">કરવેરો ભરવાની સરળ રીત</h2>
  <p class="ss">Pay property tax from your phone in three steps. No app installation needed.</p>
  <div class="steps">
    <div class="step">
      <div class="sn2">01</div>
      <div class="sico"><i class="ti ti-brand-whatsapp" style="color:#25D366"></i></div>
      <h4>Send a message</h4>
      <p>Send "Hi" to the official Panchayat WhatsApp number to start the automated assistant.</p>
    </div>
    <div class="step">
      <div class="sn2">02</div>
      <div class="sico"><i class="ti ti-file-invoice"></i></div>
      <h4>Verify your details</h4>
      <p>Enter your house number or mobile to view your outstanding property and water tax instantly.</p>
    </div>
    <div class="step">
      <div class="sn2">03</div>
      <div class="sico"><i class="ti ti-receipt"></i></div>
      <h4>Pay &amp; get receipt</h4>
      <p>Pay via GPay, PhonePe, or any UPI. Official signed PDF receipt delivered on WhatsApp.</p>
    </div>
  </div>
  <div class="cta-row">
    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-wa" style="display:inline-flex">Try the live bot <i class="ti ti-arrow-right" style="font-size:14px"></i></a>
  </div>
</div>
</div>

<!-- SERVICES -->
<div style="background:var(--white)" id="services">
<div class="sec">
  <div class="svc-lay">
    <div class="svc-sticky">
      <div class="tag">Services</div>
      <h2 class="sh t-gu">ઓનલાઇન સેવાઓ</h2>
      <p class="ss" style="margin-top:8px">Citizen services delivered directly to your smartphone.</p>
      <div class="iso-card">
        <div class="iso-card-head"><i class="ti ti-certificate"></i><span>ISO Certified Process</span></div>
        <p>All transactions are end-to-end encrypted and recorded in the Panchayat system.</p>
      </div>
    </div>
    <div class="svc-list">
      <div class="si">
        <div class="si-ico"><i class="ti ti-home"></i></div>
        <div><h4>મિલકત વેરો</h4><p>Yearly property taxes for residential and commercial spaces</p></div>
        <span class="badge-a">Active</span>
      </div>
      <div class="si">
        <div class="si-ico"><i class="ti ti-droplet"></i></div>
        <div><h4>પાણી વેરો</h4><p>Clear water connection bills without visiting the office</p></div>
        <span class="badge-a">Active</span>
      </div>
      <div class="si">
        <div class="si-ico"><i class="ti ti-speakerphone"></i></div>
        <div><h4>ગ્રામ પંચાયત નોટિસ</h4><p>Announcements and Gram Sabha schedules on WhatsApp</p></div>
        <span class="badge-a">Active</span>
      </div>
      <div class="si" style="opacity:.5;cursor:default">
        <div class="si-ico"><i class="ti ti-file-certificate"></i></div>
        <div><h4>પ્રમાણપત્રો</h4><p>Official certificates — coming in Phase 2</p></div>
        <span class="badge-s">Soon</span>
      </div>
    </div>
  </div>
</div>
</div>

<!-- INITIATIVE -->
<div class="init">
  <div class="init-i">
    <div>
      <div class="tag">Platform</div>
      <h2 class="sh">Empowering<br>Digital India</h2>
      <p class="init-desc">{{ $village->name_local }} Gram Panchayat pioneers smart governance. This secure, scalable framework is built on the CISETU Platform by Clonza Infotech.</p>
    </div>
    <div class="init-card">
      <div class="ip"><span class="ip-k">Platform</span><span class="ip-v">CISETU</span></div>
      <div class="ip"><span class="ip-k">Technology Partner</span><span class="ip-v">Clonza Infotech</span></div>
      <div class="ip"><span class="ip-k">Payment Gateway</span><span class="ip-v">UPI / Razorpay</span></div>
      <div class="ip"><span class="ip-k">Uptime SLA</span><span class="ip-v">99.9%</span></div>
      <div class="ip"><span class="ip-k">Security</span><span class="ip-v">End-to-end Encrypted</span></div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="fi">
    <div class="ft">
      <div>
        <div class="fb-row">
          <div class="fb-sq">
            @if($village->logo)
                <img src="{{ Storage::url($village->logo) }}" alt="Logo" onerror="this.src='{{ asset('assets/images/logo.jpeg') }}'">
            @else
                <i class="ti ti-building-community" aria-hidden="true"></i>
            @endif
          </div>
          <div><div class="fb-n">ગ્રામ પંચાયત — {{ $village->name_local }}</div><div class="fb-s">Digital Citizen Portal</div></div>
        </div>
        <p class="fa">Committed to transparent governance and making citizen services accessible through modern digital solutions.</p>
        <div class="soc">
          <a href="#" class="sl2"><i class="ti ti-brand-facebook"></i></a>
          <a href="#" class="sl2"><i class="ti ti-brand-x"></i></a>
        </div>
      </div>
      <div class="fc">
        <h5>Navigation</h5>
        <ul>
          <li><a href="#hero">Home</a></li>
          <li><a href="#how">How to Pay</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="fc">
        <h5>Services</h5>
        <ul>
          <li><a href="#">Property Tax</a></li>
          <li><a href="#">Water Tax</a></li>
          <li><a href="#">Notices</a></li>
          <li><a href="#">Certificates</a></li>
        </ul>
      </div>
      <div class="fc">
        <h5>Contact</h5>
        <div class="cr"><i class="ti ti-map-pin"></i><span class="t-gu">ગ્રામ પંચાયત કચેરી, {{ $village->name_local }}, Gujarat</span></div>
        <div class="cr"><i class="ti ti-mail"></i><a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a></div>
        <div class="cr"><i class="ti ti-brand-whatsapp" style="color:#25D366"></i><a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}">+91 {{ $village->whatsapp_number }}</a></div>
      </div>
    </div>
    <div class="fb2">
      <span>&copy; {{ date('Y') }} CISETU. All Rights Reserved.</span>
      <div class="pb">Powered by <strong>CISETU / Clonza Infotech</strong></div>
    </div>
  </div>
</footer>

</body>
</html>
