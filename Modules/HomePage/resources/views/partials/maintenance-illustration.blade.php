{{-- Ilustração animada para página de manutenção - tema bancário --}}
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 520 420" fill="none" aria-hidden="true" class="w-full h-auto max-w-md mx-auto">
  <defs>
    <linearGradient id="maintBankBlue1" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0ea5e9"/>
      <stop offset="100%" stop-color="#0369a1"/>
    </linearGradient>
    <linearGradient id="maintBankBlue2" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#0c4a6e"/>
      <stop offset="100%" stop-color="#0e7490"/>
    </linearGradient>
    <linearGradient id="maintShieldGrad" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#38bdf8"/>
      <stop offset="100%" stop-color="#0284c7"/>
    </linearGradient>
    <filter id="maintSoftShadow">
      <feDropShadow dx="0" dy="4" stdDeviation="8" flood-opacity="0.15"/>
    </filter>
  </defs>
  <style>
    @keyframes maintGearRotate { to { transform: rotate(360deg); } }
    @keyframes maintGearRotateRev { to { transform: rotate(-360deg); } }
    @keyframes maintPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
    @keyframes maintDotPulse { 0%, 80%, 100% { opacity: 0.3; } 40% { opacity: 1; } }
    .maint-gear-1 { animation: maintGearRotate 8s linear infinite; transform-origin: 130px 130px; }
    .maint-gear-2 { animation: maintGearRotateRev 6s linear infinite; transform-origin: 50px 50px; }
    .maint-shield-pulse { animation: maintPulse 2.5s ease-in-out infinite; }
    .maint-dot { animation: maintDotPulse 1.4s ease-in-out infinite both; }
    .maint-dot:nth-child(2) { animation-delay: 0.2s; }
    .maint-dot:nth-child(3) { animation-delay: 0.4s; }
  </style>

  <!-- Background circles - banking subtle -->
  <circle cx="80" cy="380" r="40" fill="#0c4a6e" opacity="0.06"/>
  <circle cx="440" cy="50" r="35" fill="#0e7490" opacity="0.08"/>
  <circle cx="460" cy="380" r="30" fill="#0369a1" opacity="0.05"/>

  <!-- Large gear - main system -->
  <g class="maint-gear-1" transform="translate(90,70)" filter="url(#maintSoftShadow)">
    <circle cx="130" cy="130" r="85" fill="url(#maintBankBlue1)" opacity="0.12"/>
    <path d="M130 55 L138 78 L162 74 L148 94 L168 118 L144 122 L130 145 L116 122 L92 118 L112 94 L98 74 L122 78 Z M130 95 L142 110 L130 125 L118 110 Z" fill="url(#maintBankBlue1)"/>
    <circle cx="130" cy="130" r="42" fill="white"/>
    <circle cx="130" cy="130" r="32" fill="url(#maintBankBlue1)"/>
    <circle cx="130" cy="130" r="18" fill="white"/>
  </g>

  <!-- Small gear - meshed -->
  <g class="maint-gear-2" transform="translate(320,210)" filter="url(#maintSoftShadow)">
    <circle cx="50" cy="50" r="38" fill="url(#maintBankBlue2)" opacity="0.15"/>
    <path d="M50 18 L56 34 L72 30 L62 46 L74 62 L58 66 L50 82 L42 66 L26 62 L38 46 L28 30 L44 34 Z" fill="#0e7490"/>
    <circle cx="50" cy="50" r="16" fill="white"/>
    <circle cx="50" cy="50" r="10" fill="#0c4a6e"/>
  </g>

  <!-- Shield - security / banking -->
  <g class="maint-shield-pulse" transform="translate(200,180)" filter="url(#maintSoftShadow)">
    <path d="M60 0 L120 20 L120 55 C120 95 90 130 60 150 C30 130 0 95 0 55 L0 20 Z" fill="url(#maintShieldGrad)"/>
    <path d="M60 35 L75 55 L55 75 L45 65 L60 50 Z" fill="white" opacity="0.95"/>
  </g>

  <!-- Database cylinder - system data -->
  <g transform="translate(100,250)" filter="url(#maintSoftShadow)">
    <ellipse cx="60" cy="50" rx="45" ry="12" fill="#0e7490" opacity="0.2"/>
    <path d="M15 50 L15 90 Q60 110 105 90 L105 50 Q60 68 15 50 Z" fill="url(#maintBankBlue2)" opacity="0.9"/>
    <path d="M15 50 Q60 32 105 50" stroke="#38bdf8" stroke-width="2" fill="none" opacity="0.6"/>
    <line x1="60" y1="50" x2="60" y2="90" stroke="#38bdf8" stroke-width="2" opacity="0.4"/>
  </g>

  <!-- Progress bar - "Atualizando sistema" feel -->
  <g transform="translate(80,365)">
    <rect x="0" y="0" width="360" height="8" rx="4" fill="#e2e8f0"/>
    <rect x="0" y="0" width="0" height="8" rx="4" fill="url(#maintBankBlue1)">
      <animate attributeName="width" values="0;216;0" dur="3s" repeatCount="indefinite"/>
    </rect>
  </g>

  <!-- Loading dots -->
  <g transform="translate(430,200)">
    <circle class="maint-dot" cx="0" cy="0" r="6" fill="#0369a1"/>
    <circle class="maint-dot" cx="24" cy="0" r="6" fill="#0369a1"/>
    <circle class="maint-dot" cx="48" cy="0" r="6" fill="#0369a1"/>
  </g>

  <!-- Connection nodes - network feel -->
  <circle cx="380" cy="120" r="4" fill="#0ea5e9" opacity="0.5">
    <animate attributeName="opacity" values="0.5;1;0.5" dur="1.5s" repeatCount="indefinite"/>
  </circle>
  <circle cx="395" cy="135" r="4" fill="#0284c7" opacity="0.5">
    <animate attributeName="opacity" values="0.5;1;0.5" dur="1.5s" begin="0.3s" repeatCount="indefinite"/>
  </circle>
  <circle cx="365" cy="140" r="4" fill="#0e7490" opacity="0.5">
    <animate attributeName="opacity" values="0.5;1;0.5" dur="1.5s" begin="0.6s" repeatCount="indefinite"/>
  </circle>
</svg>
