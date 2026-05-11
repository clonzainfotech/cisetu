<!DOCTYPE html>
<html lang="gu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $village->name_local }} | Digital Property Tax Portal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Syne:wght@400;500;600;700;800&family=Noto+Sans+Gujarati:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --night:#0b0f1a;--night2:#111827;--night3:#1a2235;--night4:#243047;
  --slate:#2d3a50;--slate2:#3d4f6b;
  --gold:#c9a84c;--gold2:#e8c96b;--gold3:#f5e09a;--gold4:rgba(201,168,76,0.12);
  --mist:#8899b4;--mist2:#6677a0;
  --offwhite:#f0ece4;--offwhite2:#e4ddd0;--offwhite3:#cfc8b8;
  --wa:#25D366;--wa2:#1aab52;
  --serif:'Cormorant Garamond',Georgia,serif;
  --sans:'Syne',sans-serif;
  --gu:'Noto Sans Gujarati','Syne',sans-serif;
  --r:0;
}
html{scroll-behavior:smooth}
body{font-family:var(--sans);background:var(--night);color:var(--offwhite);-webkit-font-smoothing:antialiased;overflow-x:hidden}

/* CURSOR */
*{cursor:crosshair}
a,button{cursor:crosshair}

/* GRID OVERLAY */
body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(255,255,255,.016) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.016) 1px,transparent 1px);background-size:80px 80px;pointer-events:none;z-index:0}

/* NAV */
nav{position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 48px}
nav::after{content:'';position:absolute;bottom:0;left:48px;right:48px;height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,0.3),transparent)}
.nav-i{height:72px;display:flex;align-items:center;justify-content:space-between}
.brand{display:flex;align-items:center;gap:14px;text-decoration:none}
.brand-mark{width:40px;height:40px;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative}
.brand-mark::before{content:'';position:absolute;inset:3px;border:1px solid rgba(201,168,76,0.15)}
.brand-mark img{width:100%;height:100%;object-fit:cover}
.brand-mark i{font-size:18px;color:var(--gold)}
.brand-text{display:flex;flex-direction:column}
.brand-n{font-size:13px;font-weight:700;color:var(--offwhite);letter-spacing:.08em;text-transform:uppercase;font-family:var(--gu)}
.brand-s{font-size:9.5px;color:var(--mist);letter-spacing:.2em;text-transform:uppercase;font-weight:400;margin-top:1px}
.nav-r{display:flex;align-items:center;gap:6px}
.nav-r a{font-size:11.5px;font-weight:500;color:var(--mist);text-decoration:none;padding:7px 16px;letter-spacing:.08em;text-transform:uppercase;transition:color .2s}
.nav-r a:hover{color:var(--gold)}
.nav-cta{border:1px solid rgba(201,168,76,0.4)!important;color:var(--gold)!important;display:flex;align-items:center;gap:7px;transition:all .2s!important;background:var(--gold4)}
.nav-cta:hover{background:rgba(201,168,76,0.18)!important;border-color:var(--gold)!important}
.nav-cta i{font-size:16px;color:var(--wa)}

/* HERO */
.hero{min-height:100vh;display:flex;flex-direction:column;justify-content:flex-end;padding:0 48px 0;position:relative;overflow:hidden;padding-top:72px}

/* BIG YEAR / WATERMARK */
.hero-watermark{position:absolute;right:-40px;top:50%;transform:translateY(-55%);font-family:var(--serif);font-size:clamp(180px,22vw,280px);font-weight:300;color:rgba(255,255,255,0.022);line-height:1;pointer-events:none;user-select:none;letter-spacing:-.05em;white-space:nowrap}

/* DIAGONAL LINE ACCENT */
.hero-line{position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,transparent 0%,var(--gold) 30%,var(--gold) 70%,transparent 100%)}
.hero-line::after{content:'';position:absolute;top:50%;left:4px;width:60px;height:1px;background:linear-gradient(90deg,var(--gold),transparent)}

.hero-content{position:relative;z-index:2;display:grid;grid-template-columns:1fr 460px;gap:0;align-items:end;padding-bottom:80px;min-height:calc(100vh - 72px)}
.hero-left{padding-bottom:0}
.hero-tag{display:flex;align-items:center;gap:12px;margin-bottom:32px}
.hero-tag-line{width:32px;height:1px;background:var(--gold)}
.hero-tag span{font-size:10.5px;font-weight:600;color:var(--gold);letter-spacing:.25em;text-transform:uppercase}
.hero-tag-dot{width:4px;height:4px;border-radius:50%;background:var(--gold);animation:blink 2s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}

.hero h1{font-family:var(--serif);font-size:clamp(3.5rem,6.5vw,7rem);font-weight:300;line-height:.95;letter-spacing:-.03em;color:var(--offwhite);margin-bottom:40px}
.hero h1 em{font-style:italic;color:var(--gold);display:block}
.hero h1 .sub-h{font-size:clamp(1.8rem,3vw,3.2rem);color:var(--mist);display:block;margin-top:8px}

.hero-actions{display:flex;align-items:center;gap:20px;margin-bottom:48px}
.btn-gold{display:inline-flex;align-items:center;gap:10px;padding:14px 32px;background:var(--gold);color:var(--night);font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;text-decoration:none;transition:all .2s}
.btn-gold:hover{background:var(--gold2);transform:translate(2px,-2px);box-shadow:-4px 4px 0 rgba(201,168,76,0.3)}
.btn-gold i{font-size:18px;color:var(--wa2)}
.btn-outline-gold{display:inline-flex;align-items:center;gap:9px;padding:13px 24px;border:1px solid rgba(201,168,76,0.35);color:var(--gold);font-size:12px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;transition:all .2s}
.btn-outline-gold:hover{background:var(--gold4);border-color:var(--gold)}

.hero-trust{display:flex;gap:0;border-top:1px solid rgba(255,255,255,.06)}
.ht{padding:16px 28px 16px 0;display:flex;align-items:center;gap:8px;font-size:11px;color:var(--mist);letter-spacing:.06em;text-transform:uppercase;border-right:1px solid rgba(255,255,255,.06)}
.ht:last-child{border-right:none}
.ht i{font-size:15px;color:var(--gold);flex-shrink:0}

/* HERO RIGHT — PHONE */
.hero-right{display:flex;flex-direction:column;justify-content:flex-end;align-items:flex-end;padding-bottom:80px;padding-left:40px;border-left:1px solid rgba(255,255,255,.04)}
.phone-outer{position:relative}
.phone-corner{position:absolute;width:20px;height:20px;border-color:var(--gold);border-style:solid;opacity:.4}
.phone-corner.tl{top:-10px;left:-10px;border-width:2px 0 0 2px}
.phone-corner.tr{top:-10px;right:-10px;border-width:2px 2px 0 0}
.phone-corner.bl{bottom:-10px;left:-10px;border-width:0 0 2px 2px}
.phone-corner.br{bottom:-10px;right:-10px;border-width:0 2px 2px 0}
.phone-frame{width:260px;background:#0a0a0a;border:1px solid rgba(255,255,255,.08);overflow:hidden;box-shadow:0 40px 80px rgba(0,0,0,.6),inset 0 1px 0 rgba(255,255,255,.05);animation:levitate 6s ease-in-out infinite}
@keyframes levitate{0%,100%{transform:translateY(0) rotate(-1deg)}50%{transform:translateY(-12px) rotate(.5deg)}}
.pnotch{height:24px;background:#0a0a0a;display:flex;align-items:center;justify-content:center}
.pnotch-bar{width:88px;height:5px;background:#151515;border-radius:3px}
.wh{background:linear-gradient(180deg,#1a6b3a,#0d5a2e);padding:13px 14px;display:flex;align-items:center;gap:9px}
.wh-av{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0}
.wh-av img{width:100%;height:100%;object-fit:cover}
.wh-av i{font-size:15px;color:#fff}
.wh-nm{font-size:12.5px;font-weight:700;color:#fff;letter-spacing:.02em}
.wh-st{font-size:9.5px;color:rgba(255,255,255,.5)}
.chat-area{background:#0f0f0f;background-image:radial-gradient(circle at 1px 1px,rgba(255,255,255,.03) 1px,transparent 0);background-size:20px 20px;padding:14px 11px;display:flex;flex-direction:column;gap:9px;min-height:290px}
.cm-out{align-self:flex-end;background:linear-gradient(135deg,#1a4a28,#14391e);padding:8px 12px;border-radius:12px 12px 2px 12px;font-size:11px;color:#c8f0d4;max-width:80%}
.cm-in{align-self:flex-start;background:#181818;border:1px solid rgba(255,255,255,.07);padding:10px 12px;border-radius:12px 12px 12px 2px;font-size:11px;color:#ccc;max-width:88%;line-height:1.5}
.cm-in strong{display:block;font-size:12px;font-weight:700;color:var(--gold);margin-bottom:5px;letter-spacing:.04em}
.cm-pay{display:block;margin-top:9px;padding:7px 12px;background:var(--gold);color:var(--night);text-align:center;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;text-decoration:none}
.cm-receipt{align-self:flex-start;background:#0d1a12;border:1px solid rgba(26,107,58,.4);padding:10px 12px;border-radius:12px 12px 12px 2px;display:flex;align-items:center;gap:10px}
.recv-ic{width:26px;height:26px;border:1px solid rgba(26,107,58,.5);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.recv-ic i{font-size:13px;color:var(--wa2)}
.recv-t{font-size:11.5px;font-weight:700;color:var(--wa2);letter-spacing:.04em}
.recv-s{font-size:10px;color:rgba(255,255,255,.3)}

/* HERO SCROLL HINT */
.scroll-hint{position:absolute;bottom:32px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:6px;font-size:9.5px;color:var(--mist);letter-spacing:.18em;text-transform:uppercase;z-index:10}
.scroll-line{width:1px;height:32px;background:linear-gradient(180deg,var(--gold),transparent);animation:scroll-drop 1.8s ease-in-out infinite}
@keyframes scroll-drop{0%,100%{transform:scaleY(0);transform-origin:top}50%{transform:scaleY(1);transform-origin:top}}

/* STATS */
.stats-bar{background:var(--gold);position:relative;overflow:hidden}
.stats-bar::before{content:'';position:absolute;inset:0;background:repeating-linear-gradient(90deg,transparent,transparent 120px,rgba(0,0,0,.05) 120px,rgba(0,0,0,.05) 121px)}
.stats-i{max-width:1400px;margin:auto;display:grid;grid-template-columns:repeat(4,1fr)}
.sc{padding:26px 40px;border-right:1px solid rgba(0,0,0,.12);display:flex;align-items:center;gap:16px}
.sc:last-child{border-right:none}
.sc-num{font-family:var(--serif);font-size:2.8rem;font-weight:300;color:var(--night);line-height:1;letter-spacing:-.04em}
.sc-label{font-size:10.5px;font-weight:700;color:rgba(0,0,0,.5);letter-spacing:.15em;text-transform:uppercase;line-height:1.4}

/* HOW */
.how-section{background:var(--night2);border-top:1px solid rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.04)}
.sec-wrap{max-width:1200px;margin:auto;padding:96px 48px}
.sec-tag{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.sec-tag-n{font-size:10px;font-weight:700;color:var(--gold);letter-spacing:.25em;text-transform:uppercase}
.sec-tag-bar{flex:1;height:1px;background:linear-gradient(90deg,rgba(201,168,76,.3),transparent)}
.sec-h{font-family:var(--serif);font-size:clamp(2.2rem,3.8vw,4rem);font-weight:300;color:var(--offwhite);letter-spacing:-.03em;line-height:1.05;margin-bottom:14px}
.sec-h em{font-style:italic;color:var(--gold)}
.sec-sub{font-size:14px;color:var(--mist);line-height:1.8;max-width:500px;font-weight:400}
.steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:rgba(255,255,255,.04);margin-top:56px}
.step-card{background:var(--night2);padding:40px 32px;position:relative;overflow:hidden;transition:background .2s}
.step-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--gold),transparent);transform:scaleX(0);transform-origin:left;transition:transform .35s}
.step-card:hover{background:var(--night3)}
.step-card:hover::after{transform:scaleX(1)}
.step-no{font-family:var(--serif);font-size:5.5rem;font-weight:300;color:rgba(201,168,76,.08);line-height:1;position:absolute;top:20px;right:24px;letter-spacing:-.04em;font-style:italic}
.step-ico{width:44px;height:44px;border:1px solid rgba(201,168,76,.3);display:flex;align-items:center;justify-content:center;margin-bottom:22px;position:relative;z-index:1}
.step-ico i{font-size:20px;color:var(--gold)}
.step-card h4{font-size:15px;font-weight:700;color:var(--offwhite);margin-bottom:10px;letter-spacing:.04em;text-transform:uppercase;position:relative;z-index:1}
.step-card p{font-size:13px;color:var(--mist);line-height:1.75;font-weight:400;position:relative;z-index:1}
.how-cta{margin-top:48px;display:flex;align-items:center;gap:20px}

/* SERVICES */
.svc-section{background:var(--night)}
.svc-layout{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(255,255,255,.04)}
.svc-intro{background:var(--night);padding:80px 48px;display:flex;flex-direction:column;justify-content:space-between;min-height:540px}
.svc-list-wrap{background:var(--night);padding:0}
.svc-item{padding:28px 40px;border-bottom:1px solid rgba(255,255,255,.04);display:grid;grid-template-columns:48px 1fr 80px;gap:20px;align-items:center;transition:background .15s;position:relative;overflow:hidden}
.svc-item::before{content:'';position:absolute;left:0;top:0;bottom:0;width:2px;background:var(--gold);transform:scaleY(0);transition:transform .25s}
.svc-item:hover{background:var(--night3)}
.svc-item:hover::before{transform:scaleY(1)}
.svc-item.dim{opacity:.4;pointer-events:none}
.svc-ico-wrap{width:48px;height:48px;border:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.svc-ico-wrap i{font-size:22px;color:var(--gold)}
.svc-item h4{font-size:13.5px;font-weight:700;color:var(--offwhite);letter-spacing:.05em;text-transform:uppercase;margin-bottom:4px;font-family:var(--gu)}
.svc-item p{font-size:12px;color:var(--mist);font-weight:400}
.badge-live{font-size:9.5px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--wa2);border:1px solid rgba(37,211,102,.25);padding:4px 10px;text-align:center}
.badge-next{font-size:9.5px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--mist);border:1px solid rgba(255,255,255,.1);padding:4px 10px;text-align:center}
.iso-block{background:var(--night3);border:1px solid rgba(201,168,76,.15);padding:20px;display:flex;gap:14px;margin-top:32px}
.iso-block i{font-size:24px;color:var(--gold);flex-shrink:0}
.iso-block h5{font-size:12px;font-weight:700;color:var(--offwhite);letter-spacing:.08em;text-transform:uppercase;margin-bottom:6px}
.iso-block p{font-size:12px;color:var(--mist);line-height:1.6}

/* PLATFORM */
.platform-section{background:var(--night2);position:relative;overflow:hidden}
.platform-section::before{content:'';position:absolute;left:-200px;bottom:-200px;width:600px;height:600px;border:1px solid rgba(201,168,76,.05);border-radius:50%;pointer-events:none}
.platform-section::after{content:'';position:absolute;left:-100px;bottom:-100px;width:400px;height:400px;border:1px solid rgba(201,168,76,.04);border-radius:50%;pointer-events:none}
.platform-inner{max-width:1200px;margin:auto;padding:96px 48px;display:grid;grid-template-columns:1fr 480px;gap:80px;align-items:center;position:relative;z-index:1}
.platform-label{display:flex;align-items:center;gap:10px;margin-bottom:20px}
.platform-label span{font-size:10px;font-weight:700;color:var(--gold);letter-spacing:.25em;text-transform:uppercase}
.platform-label-line{width:40px;height:1px;background:rgba(201,168,76,.4)}
.platform-h{font-family:var(--serif);font-size:clamp(2.4rem,4vw,4.5rem);font-weight:300;color:var(--offwhite);line-height:.95;letter-spacing:-.04em;margin-bottom:16px}
.platform-h em{font-style:italic;color:var(--gold)}
.platform-desc{font-size:13.5px;color:var(--mist);line-height:1.85;font-weight:400;max-width:380px}
.tech-table{border:1px solid rgba(255,255,255,.06);overflow:hidden}
.tech-row{display:grid;grid-template-columns:1fr 1fr;border-bottom:1px solid rgba(255,255,255,.05);transition:background .15s}
.tech-row:last-child{border-bottom:none}
.tech-row:hover{background:rgba(255,255,255,.02)}
.tech-k{padding:16px 20px;font-size:12px;color:var(--mist);border-right:1px solid rgba(255,255,255,.05);letter-spacing:.04em;font-weight:400}
.tech-v{padding:16px 20px;font-size:12px;font-weight:700;color:var(--offwhite);letter-spacing:.04em}

/* CTA */
.cta-section{background:var(--night);padding:120px 48px;text-align:center;position:relative;overflow:hidden}
.cta-section::before{content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:600px;border:1px solid rgba(201,168,76,.06);border-radius:50%;pointer-events:none}
.cta-section::after{content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:900px;height:900px;border:1px solid rgba(201,168,76,.03);border-radius:50%;pointer-events:none}
.cta-inner{position:relative;z-index:1;max-width:640px;margin:auto}
.cta-kicker{font-size:10px;font-weight:700;color:var(--gold);letter-spacing:.3em;text-transform:uppercase;margin-bottom:24px;display:flex;align-items:center;justify-content:center;gap:12px}
.cta-kicker::before,.cta-kicker::after{content:'';display:block;width:40px;height:1px;background:rgba(201,168,76,.4)}
.cta-h{font-family:var(--serif);font-size:clamp(3rem,5.5vw,6rem);font-weight:300;color:var(--offwhite);line-height:.95;letter-spacing:-.04em;margin-bottom:20px}
.cta-h em{font-style:italic;color:var(--gold)}
.cta-sub{font-size:14px;color:var(--mist);line-height:1.8;margin-bottom:48px;font-weight:400}
.btn-cta{display:inline-flex;align-items:center;gap:10px;padding:16px 40px;background:var(--gold);color:var(--night);font-size:12px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;text-decoration:none;transition:all .25s;position:relative}
.btn-cta::before{content:'';position:absolute;inset:-3px;border:1px solid rgba(201,168,76,.3);opacity:0;transition:opacity .2s}
.btn-cta:hover{background:var(--gold2);transform:translate(3px,-3px);box-shadow:-6px 6px 0 rgba(201,168,76,.2)}
.btn-cta:hover::before{opacity:1}
.btn-cta i{font-size:20px;color:var(--wa2)}

/* FOOTER */
footer{background:var(--night2);border-top:1px solid rgba(255,255,255,.05)}
.footer-grid{max-width:1200px;margin:auto;padding:64px 48px 36px;display:grid;grid-template-columns:2.5fr 1fr 1fr 1.5fr;gap:48px;border-bottom:1px solid rgba(255,255,255,.05);margin-bottom:0}
.footer-brand-wrap{display:flex;align-items:center;gap:14px;margin-bottom:20px}
.footer-brand-sq{width:40px;height:40px;border:1px solid rgba(201,168,76,.35);display:flex;align-items:center;justify-content:center;overflow:hidden}
.footer-brand-sq img{width:100%;height:100%;object-fit:cover}
.footer-brand-sq i{font-size:18px;color:var(--gold)}
.footer-brand-n{font-size:13.5px;font-weight:700;color:var(--offwhite);letter-spacing:.06em;text-transform:uppercase;font-family:var(--gu)}
.footer-brand-s{font-size:9px;color:var(--mist);letter-spacing:.18em;text-transform:uppercase;margin-top:2px}
.footer-desc{font-size:12.5px;color:var(--mist);line-height:1.8;max-width:280px;margin-bottom:20px;font-weight:400}
.footer-socials{display:flex;gap:8px}
.fsoc{width:32px;height:32px;border:1px solid rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;font-size:15px;color:var(--mist);text-decoration:none;transition:all .2s}
.fsoc:hover{border-color:rgba(201,168,76,.5);color:var(--gold)}
.footer-col h5{font-size:9.5px;font-weight:700;color:var(--gold);letter-spacing:.22em;text-transform:uppercase;margin-bottom:18px;padding-bottom:10px;border-bottom:1px solid rgba(201,168,76,.15)}
.footer-col ul{list-style:none}
.footer-col li{margin-bottom:11px}
.footer-col a{font-size:12.5px;color:var(--mist);text-decoration:none;font-weight:400;letter-spacing:.02em;transition:color .15s}
.footer-col a:hover{color:var(--offwhite)}
.fcontact-row{display:flex;align-items:flex-start;gap:10px;margin-bottom:12px}
.fcontact-row i{font-size:15px;color:rgba(201,168,76,.5);flex-shrink:0;margin-top:1px}
.fcontact-row a,.fcontact-row span{font-size:12px;color:var(--mist);text-decoration:none;line-height:1.55;font-weight:400;transition:color .15s}
.fcontact-row a:hover{color:var(--offwhite)}
.fcontact-row .wa-c{color:rgba(37,211,102,.5)}
.fcontact-row .wa-c:hover{color:var(--wa)}
.footer-bottom{max-width:1200px;margin:auto;padding:20px 48px;display:flex;align-items:center;justify-content:space-between;font-size:11px;color:rgba(255,255,255,.2);flex-wrap:wrap;gap:8px}
.footer-badge{display:inline-flex;align-items:center;gap:8px;padding:5px 14px;border:1px solid rgba(255,255,255,.07);font-size:10.5px;color:rgba(255,255,255,.25);letter-spacing:.08em}
.footer-badge strong{color:rgba(201,168,76,.6);font-weight:600}

/* RESPONSIVE */
@media(max-width:960px){
  .hero-content,.platform-inner{grid-template-columns:1fr}
  .hero-right{display:none}
  .svc-layout{grid-template-columns:1fr}
  .stats-i{grid-template-columns:repeat(2,1fr)}
  .sc{border-right:none;border-bottom:1px solid rgba(0,0,0,.12)}
  .steps-grid{grid-template-columns:1fr}
  .footer-grid{grid-template-columns:1fr 1fr;gap:32px}
}
@media(max-width:640px){
  nav{padding:0 20px}
  nav::after{left:20px;right:20px}
  .nav-r a:not(.nav-cta){display:none}
  .hero{padding:0 20px}
  .hero-content{padding-bottom:56px}
  .hero-line{display:none}
  .sec-wrap,.platform-inner,.cta-section{padding:64px 20px}
  .footer-grid{grid-template-columns:1fr;padding:48px 20px 32px}
  .footer-bottom{padding:16px 20px}
  .svc-intro{padding:48px 24px}
  .svc-item{padding:20px 24px}
}
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="nav-i">
    <a href="#" class="brand">
      <div class="brand-mark">
        @if($village->logo)
          <img src="{{ Storage::url($village->logo) }}" alt="Logo">
        @else
          <i class="ti ti-building-community" aria-hidden="true"></i>
        @endif
      </div>
      <div class="brand-text">
        <div class="brand-n">ગ્રામ પંચાયત {{ $village->name_local }}</div>
        <div class="brand-s">Smart Village Portal</div>
      </div>
    </a>
    <div class="nav-r">
      <a href="#hero">Home</a>
      <a href="#how">Process</a>
      <a href="#services">Services</a>
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="nav-cta"><i class="ti ti-brand-whatsapp"></i> Pay Tax</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-watermark" aria-hidden="true">CISETU</div>
  <div class="hero-line" aria-hidden="true"></div>

  <div class="hero-content">
    <div class="hero-left">
      <div class="hero-tag">
        <div class="hero-tag-line"></div>
        <span>Official Digital Tax Portal</span>
        <div class="hero-tag-dot"></div>
      </div>
      <h1>
        Property<br>
        <em>Tax Made</em>
        <span class="sub-h">Simple.</span>
      </h1>
      <div class="hero-actions">
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-gold">
          <i class="ti ti-brand-whatsapp"></i> Pay on WhatsApp
        </a>
        <a href="#how" class="btn-outline-gold">
          <i class="ti ti-arrow-down-right" style="font-size:16px"></i> See How
        </a>
      </div>
      <div class="hero-trust">
        <span class="ht"><i class="ti ti-shield-lock" aria-hidden="true"></i>&nbsp;Secure</span>
        <span class="ht"><i class="ti ti-bolt" aria-hidden="true"></i>&nbsp;Instant</span>
        <span class="ht"><i class="ti ti-currency-rupee" aria-hidden="true"></i>&nbsp;Zero Fee</span>
        <span class="ht"><i class="ti ti-clock-24" aria-hidden="true"></i>&nbsp;24 / 7</span>
      </div>
    </div>

    <!-- PHONE -->
    <div class="hero-right">
      <div class="phone-outer">
        <div class="phone-corner tl"></div>
        <div class="phone-corner tr"></div>
        <div class="phone-corner bl"></div>
        <div class="phone-corner br"></div>
        <div class="phone-frame">
          <div class="pnotch"><div class="pnotch-bar"></div></div>
          <div class="wh">
            <div class="wh-av">
              @if($village->logo)
                <img src="{{ Storage::url($village->logo) }}" alt="logo">
              @else
                <i class="ti ti-building-community" aria-hidden="true"></i>
              @endif
            </div>
            <div style="flex:1">
              <div class="wh-nm">{{ $village->name_local }} GP</div>
              <div class="wh-st">Official Portal · Online</div>
            </div>
            <i class="ti ti-dots-vertical" style="color:rgba(255,255,255,.5);font-size:18px" aria-hidden="true"></i>
          </div>
          <div class="chat-area">
            <div class="cm-out">Hi Panchayat 👋</div>
            <div class="cm-in">
              <strong>Welcome, Citizen</strong>
              Outstanding tax: <strong>₹ 1,250</strong><br>
              House No. A-42 · FY 2024–25
              <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="cm-pay">Pay Now →</a>
            </div>
            <div class="cm-out">Done ✓</div>
            <div class="cm-receipt">
              <div class="recv-ic"><i class="ti ti-check" aria-hidden="true"></i></div>
              <div>
                <div class="recv-t">Payment Confirmed</div>
                <div class="recv-s">Receipt on WhatsApp · ₹ 1,250</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="scroll-hint" aria-hidden="true">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- STATS -->
<div class="stats-bar">
  <div class="stats-i">
    <div class="sc"><div><div class="sc-num">2,500+</div><div class="sc-label">Homes<br>Connected</div></div></div>
    <div class="sc"><div><div class="sc-num">24 / 7</div><div class="sc-label">System<br>Uptime</div></div></div>
    <div class="sc"><div><div class="sc-num">0%</div><div class="sc-label">Convenience<br>Fee</div></div></div>
    <div class="sc"><div><div class="sc-num">100%</div><div class="sc-label">Digital<br>Accuracy</div></div></div>
  </div>
</div>

<!-- HOW IT WORKS -->
<div class="how-section" id="how">
  <div class="sec-wrap">
    <div class="sec-tag">
      <span class="sec-tag-n">Process</span>
      <div class="sec-tag-bar"></div>
    </div>
    <h2 class="sec-h">Three steps<br>to <em>pay your tax</em></h2>
    <p class="sec-sub">No app install. No office visit. No paperwork. Just WhatsApp — the one app you already have.</p>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-no" aria-hidden="true">01</div>
        <div class="step-ico"><i class="ti ti-brand-whatsapp" style="color:var(--wa)" aria-hidden="true"></i></div>
        <h4>Send a message</h4>
        <p>Send "Hi" to the official {{ $village->name_local }} Panchayat WhatsApp number to activate the automated assistant instantly.</p>
      </div>
      <div class="step-card">
        <div class="step-no" aria-hidden="true">02</div>
        <div class="step-ico"><i class="ti ti-id" aria-hidden="true"></i></div>
        <h4>Verify your details</h4>
        <p>Enter your house number or registered mobile to view all outstanding dues — property tax and water tax — in real time.</p>
      </div>
      <div class="step-card">
        <div class="step-no" aria-hidden="true">03</div>
        <div class="step-ico"><i class="ti ti-receipt" aria-hidden="true"></i></div>
        <h4>Pay &amp; receive receipt</h4>
        <p>Pay via GPay, PhonePe, or any UPI app. Signed digital receipt in PDF delivered to your WhatsApp within seconds.</p>
      </div>
    </div>
    <div class="how-cta">
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-gold"><i class="ti ti-brand-whatsapp"></i> Try the Live Bot</a>
    </div>
  </div>
</div>

<!-- SERVICES -->
<div class="svc-section" id="services">
  <div class="svc-layout">
    <div class="svc-intro">
      <div>
        <div class="sec-tag">
          <span class="sec-tag-n">Services</span>
          <div style="width:40px;height:1px;background:rgba(201,168,76,.3)"></div>
        </div>
        <h2 class="sec-h" style="font-size:clamp(2rem,3.2vw,3.5rem)">Digital<br><em>Citizen Services</em></h2>
        <p class="sec-sub" style="margin-top:12px;max-width:340px">All government services, directly to your smartphone. No queues. No middlemen.</p>
      </div>
      <div class="iso-block">
        <i class="ti ti-certificate" aria-hidden="true"></i>
        <div>
          <h5>ISO Certified Process</h5>
          <p>All transactions are end-to-end encrypted and recorded instantly in the Panchayat ledger system. Zero discrepancy guaranteed.</p>
        </div>
      </div>
    </div>
    <div class="svc-list-wrap">
      <div class="svc-item">
        <div class="svc-ico-wrap"><i class="ti ti-home" aria-hidden="true"></i></div>
        <div>
          <h4>મિલકત વેરો</h4>
          <p>Annual property tax for residential and commercial spaces — paid in seconds via WhatsApp</p>
        </div>
        <span class="badge-live">Live</span>
      </div>
      <div class="svc-item">
        <div class="svc-ico-wrap"><i class="ti ti-droplet" aria-hidden="true"></i></div>
        <div>
          <h4>પાણી વેરો</h4>
          <p>Clear water connection dues without visiting the Panchayat office</p>
        </div>
        <span class="badge-live">Live</span>
      </div>
      <div class="svc-item">
        <div class="svc-ico-wrap"><i class="ti ti-speakerphone" aria-hidden="true"></i></div>
        <div>
          <h4>ગ્રામ પંચાયત નોટિસ</h4>
          <p>Official announcements and Gram Sabha schedules delivered to your WhatsApp</p>
        </div>
        <span class="badge-live">Live</span>
      </div>
      <div class="svc-item dim">
        <div class="svc-ico-wrap"><i class="ti ti-file-certificate" aria-hidden="true"></i></div>
        <div>
          <h4>પ્રમાણપત્રો</h4>
          <p>Official certificates and documents — launching in Phase 2</p>
        </div>
        <span class="badge-next">Phase 2</span>
      </div>
    </div>
  </div>
</div>

<!-- PLATFORM -->
<div class="platform-section">
  <div class="platform-inner">
    <div>
      <div class="platform-label">
        <div class="platform-label-line"></div>
        <span>Platform</span>
      </div>
      <h2 class="platform-h">Built for<br><em>Digital India</em></h2>
      <p class="platform-desc" style="margin-top:16px">{{ $village->name_local }} Gram Panchayat is at the forefront of smart village governance. The CISETU platform, developed by Clonza Infotech, enables secure, scalable citizen services for rural Gujarat.</p>
    </div>
    <div class="tech-table">
      <div class="tech-row"><span class="tech-k">Platform</span><span class="tech-v">CISETU</span></div>
      <div class="tech-row"><span class="tech-k">Technology Partner</span><span class="tech-v">Clonza Infotech</span></div>
      <div class="tech-row"><span class="tech-k">Payment Gateway</span><span class="tech-v">UPI / Razorpay</span></div>
      <div class="tech-row"><span class="tech-k">Uptime SLA</span><span class="tech-v">99.9%</span></div>
      <div class="tech-row"><span class="tech-k">Security</span><span class="tech-v">End-to-end Encrypted</span></div>
    </div>
  </div>
</div>

<!-- CTA -->
<div class="cta-section">
  <div class="cta-inner">
    <div class="cta-kicker">Pay Your Tax</div>
    <h2 class="cta-h">Ready to<br><em>get started?</em></h2>
    <p class="cta-sub">Join thousands of {{ $village->name_local }} citizens who've already switched to the easiest way to pay property tax. It takes under 2 minutes.</p>
    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-cta">
      <i class="ti ti-brand-whatsapp"></i> Open WhatsApp Now
    </a>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-brand-wrap">
        <div class="footer-brand-sq">
          @if($village->logo)
            <img src="{{ Storage::url($village->logo) }}" alt="Logo">
          @else
            <i class="ti ti-building-community" aria-hidden="true"></i>
          @endif
        </div>
        <div>
          <div class="footer-brand-n">ગ્રામ પંચાયત {{ $village->name_local }}</div>
          <div class="footer-brand-s">Digital Citizen Portal</div>
        </div>
      </div>
      <p class="footer-desc">Committed to transparent governance and making essential citizen services accessible through modern digital infrastructure.</p>
      <div class="footer-socials">
        <a href="#" class="fsoc" aria-label="Facebook"><i class="ti ti-brand-facebook" aria-hidden="true"></i></a>
        <a href="#" class="fsoc" aria-label="X / Twitter"><i class="ti ti-brand-x" aria-hidden="true"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h5>Navigate</h5>
      <ul>
        <li><a href="#hero">Home</a></li>
        <li><a href="#how">How to Pay</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#">Privacy Policy</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Services</h5>
      <ul>
        <li><a href="#">Property Tax</a></li>
        <li><a href="#">Water Tax</a></li>
        <li><a href="#">Notices</a></li>
        <li><a href="#">Certificates</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Contact</h5>
      <div class="fcontact-row"><i class="ti ti-map-pin" aria-hidden="true"></i><span>ગ્રામ પંચાયત કચેરી, {{ $village->name_local }}, Gujarat</span></div>
      <div class="fcontact-row"><i class="ti ti-mail" aria-hidden="true"></i><a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a></div>
      <div class="fcontact-row"><i class="ti ti-brand-whatsapp" aria-hidden="true"></i><a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" class="wa-c">+91 {{ $village->whatsapp_number }}</a></div>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; {{ date('Y') }} CISETU. All Rights Reserved.</span>
    <div class="footer-badge">Powered by&nbsp;<strong>CISETU / Clonza Infotech</strong></div>
  </div>
</footer>

</body>
</html>