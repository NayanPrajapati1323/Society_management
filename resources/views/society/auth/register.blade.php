<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register – SocietyPro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <style>
    :root {
      --primary: #6C63FF; --primary-dark: #574fd6; --primary-light: #ede9ff;
      --dark: #0f0e17; --muted: #6b7280; --border: #e5e7eb;
      --gradient: linear-gradient(135deg,#6C63FF,#43e97b);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; background: #f8f7ff; }
    .auth-left {
      width: 42%;
      background: linear-gradient(160deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
      display: flex; flex-direction: column; justify-content: center; align-items: center;
      padding: 3rem; position: relative; overflow: hidden;
    }
    .auth-left::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 70% 30%, rgba(108,99,255,0.4) 0%, transparent 60%),
                  radial-gradient(ellipse at 20% 80%, rgba(67,233,123,0.2) 0%, transparent 50%);
    }
    .auth-left-content { position: relative; z-index: 1; text-align: center; max-width: 360px; }
    .auth-brand { display: inline-flex; align-items: center; gap: .6rem; text-decoration: none; margin-bottom: 2.5rem; }
    .auth-brand-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--gradient); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.3rem; }
    .auth-brand-name { font-size: 1.4rem; font-weight: 800; color: #fff; }
    .auth-brand-name span { color: #43e97b; }
    .auth-left h2 { color: #fff; font-size: 1.7rem; font-weight: 800; margin-bottom: 1rem; line-height: 1.3; }
    .auth-left p { color: rgba(255,255,255,.6); font-size: .88rem; line-height: 1.7; margin-bottom: 2rem; }
    .steps-list { text-align: left; }
    .step-item { display: flex; align-items: flex-start; gap: .85rem; padding: .85rem .75rem; border-radius: 12px; margin-bottom: .5rem; }
    .step-item:hover { background: rgba(255,255,255,.05); }
    .step-num { width: 28px; height: 28px; border-radius: 50%; background: var(--gradient); color: #fff; font-size: .75rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: .1rem; }
    .step-text strong { display: block; color: #fff; font-size: .9rem; font-weight: 600; margin-bottom: .15rem; }
    .step-text span { color: rgba(255,255,255,.55); font-size: .78rem; }

    .auth-right { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem; background: #fff; overflow-y: auto; }
    .auth-form-wrapper { width: 100%; max-width: 460px; padding: 1rem 0; }
    .auth-form-wrapper h3 { font-size: 1.6rem; font-weight: 800; color: var(--dark); margin-bottom: .35rem; }
    .auth-form-wrapper p.subtitle { color: var(--muted); font-size: .88rem; margin-bottom: 1.75rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 700; color: #374151; margin-bottom: .4rem; }
    .input-wrap { position: relative; }
    .input-wrap .input-icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 1rem; }
    .form-control, .form-select { width: 100%; padding: .75rem 1rem .75rem 2.5rem; border-radius: 12px; border: 1.5px solid var(--border); font-size: .88rem; font-family: inherit; color: #1f2937; background: #fafafa; transition: all .2s; outline: none; appearance: none; }
    .form-select { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right .75rem center; background-size: 16px 12px; }
    .form-control:focus, .form-select:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(108,99,255,.1); }
    .password-toggle { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--muted); font-size: 1.1rem; border:none; background:none; padding:0; display:flex; align-items:center; }
    .password-toggle:hover { color: var(--primary); }
    .society-info { display: none; margin-top: .5rem; font-size: .82rem; padding: .6rem 1rem; border-radius: 8px; background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; align-items: center; gap: .5rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .btn-submit { width: 100%; padding: 1rem; background: var(--gradient); color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 800; cursor: pointer; font-family: inherit; transition: all .25s; margin-top: 1rem; }
    .btn-submit:hover { opacity: .9; transform: translateY(-2px); box-shadow: 0 10px 30px rgba(108,99,255,.35); }
    .auth-link { text-align: center; font-size: .88rem; color: var(--muted); margin-top: 1.5rem; }
    .auth-link a { color: var(--primary); font-weight: 700; text-decoration: none; }
    @media (max-width: 991px) { .auth-left { display: none; } }
  </style>
</head>
<body>
<div class="auth-left">
  <div class="auth-left-content">
    <a href="{{ route('society.landing') }}" class="auth-brand">
      <div class="auth-brand-icon"><i class="bi bi-buildings-fill"></i></div>
      <span class="auth-brand-name">Society<span>Pro</span></span>
    </a>
    <h2>Join 500+ Societies on SocietyPro</h2>
    <p>Register your account and get started with the most trusted society management platform in India.</p>
    <div class="steps-list">
      <div class="step-item">
        <div class="step-num">1</div>
        <div class="step-text"><strong>Create your account</strong><span>Takes less than 2 minutes</span></div>
      </div>
      <div class="step-item">
        <div class="step-num">2</div>
        <div class="step-text"><strong>Register your society</strong><span>Submit details for admin approval</span></div>
      </div>
      <div class="step-item">
        <div class="step-num">3</div>
        <div class="step-text"><strong>Onboard members</strong><span>Add residents and start managing</span></div>
      </div>
      <div class="step-item">
        <div class="step-num">4</div>
        <div class="step-text"><strong>Go fully digital</strong><span>Bills, visitors, notices & more</span></div>
      </div>
    </div>
  </div>
</div>

<div class="auth-right">
  <div class="auth-form-wrapper">
    <h3>Create Account ✨</h3>
    <p class="subtitle">Start managing your society smarter today</p>

    @if($errors->any())
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;margin-top:1px;"></i>
      <div>
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
      </div>
    </div>
    @endif

    <form method="POST" action="{{ route('society.register.post') }}" enctype="multipart/form-data">
      @csrf
      
      <div class="form-row">
        <div class="form-group">
          <label>Full Name *</label>
          <div class="input-wrap">
            <i class="bi bi-person input-icon"></i>
            <input type="text" name="name" class="form-control" placeholder="Rajesh Kumar" required value="{{ old('name') }}" />
          </div>
        </div>
        <div class="form-group">
          <label>Email Address *</label>
          <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="you@email.com" required value="{{ old('email') }}" />
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Contact Number *</label>
          <div class="input-wrap">
            <i class="bi bi-telephone input-icon"></i>
            <input type="text" name="phone" class="form-control" placeholder="+91 987..." required value="{{ old('phone') }}" />
          </div>
        </div>
        <div class="form-group">
          <label>Country *</label>
          <div class="input-wrap">
            <i class="bi bi-geo-alt input-icon"></i>
            <input type="text" value="India" class="form-control" disabled />
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>State *</label>
          <div class="input-wrap">
            <i class="bi bi-map input-icon"></i>
            <select id="stateSelect" class="form-select" required onchange="updateCities()">
              <option value="">Select State</option>
              <option value="Gujarat">Gujarat</option>
              <option value="Maharashtra">Maharashtra</option>
              <option value="Rajasthan">Rajasthan</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>City *</label>
          <div class="input-wrap">
            <i class="bi bi-geo input-icon"></i>
            <select id="citySelect" class="form-select" required onchange="updateSocieties()">
              <option value="">Select City</option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>Select Society *</label>
        <div class="input-wrap">
          <i class="bi bi-building-check input-icon"></i>
          <select name="society_id" id="societySelect" class="form-select" required onchange="showSocietyType()">
            <option value="">Select Society</option>
          </select>
        </div>
        <div id="societyInfo" class="society-info">
          <i class="bi bi-info-circle-fill"></i>
          <span id="societyTypeText"></span>
        </div>
      </div>

      <!-- Dynamic Structure Selection -->
      <div id="flatStructure" style="display: none;">
        <div class="form-row">
          <div class="form-group">
            <label>Tower *</label>
            <div class="input-wrap">
              <i class="bi bi-building-up input-icon"></i>
              <select id="towerSelect" class="form-select" onchange="loadFloors()">
                <option value="">Select Tower</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Floor *</label>
            <div class="input-wrap">
              <i class="bi bi-layers input-icon"></i>
              <select id="floorSelect" class="form-select" onchange="loadUnits('flat')">
                <option value="">Select Floor</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div id="blockStructure" style="display: none;">
        <div class="form-group">
          <label>Block *</label>
          <div class="input-wrap">
            <i class="bi bi-grid-3x3-gap input-icon"></i>
            <select id="blockSelect" class="form-select" onchange="loadUnits('row')">
              <option value="">Select Block</option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label id="unitSelectLabel">Flat/House Number *</label>
          <div class="input-wrap">
            <i class="bi bi-door-closed input-icon"></i>
            <select name="unit_number" id="unitSelect" class="form-select" required>
              <option value="">Select Number</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>ID Proof (PNG, JPG, PDF) *</label>
          <div class="input-wrap">
            <i class="bi bi-file-earmark-arrow-up input-icon"></i>
            <input type="file" name="document" class="form-control" style="padding-left: 2.5rem;" required />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>Password *</label>
        <div class="input-wrap">
          <i class="bi bi-lock input-icon"></i>
          <input type="password" id="regPassword" name="password" class="form-control" placeholder="Minimum 6 characters" required />
          <button type="button" class="password-toggle" onclick="togglePassword('regPassword', 'passIcon')">
            <i id="passIcon" class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <div class="form-group">
        <label>Confirm Password *</label>
        <div class="input-wrap">
          <i class="bi bi-shield-lock input-icon"></i>
          <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required />
        </div>
      </div>

      <button type="submit" class="btn-submit">
        <i class="bi bi-check2-circle"></i> Register My Account
      </button>

      <p class="terms">Wait for admin approval after registration to access your dashboard.</p>
    </form>

    <div class="auth-link">
      Already have an account? <a href="{{ route('society.login') }}">Sign in</a>
    </div>
  </div>
</div>

<script>
  const citiesByState = {
    'Gujarat': ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot'],
    'Maharashtra': ['Mumbai', 'Pune', 'Nagpur', 'Thane'],
    'Rajasthan': ['Jaipur', 'Jodhpur', 'Udaipur']
  };

  let societiesData = [];

  function updateCities() {
    const state = document.getElementById('stateSelect').value;
    const citySelect = document.getElementById('citySelect');
    citySelect.innerHTML = '<option value="">Select City</option>';
    if (state && citiesByState[state]) {
      citiesByState[state].forEach(city => {
        citySelect.innerHTML += `<option value="${city}">${city}</option>`;
      });
    }
    updateSocieties();
  }

  async function updateSocieties() {
    const city = document.getElementById('citySelect').value;
    const socSelect = document.getElementById('societySelect');
    const info = document.getElementById('societyInfo');
    socSelect.innerHTML = '<option value="">Select Society</option>';
    info.style.display = 'none';

    if (!city) return;

    try {
      const response = await fetch(`/api/societies/${city}`);
      societiesData = await response.json();
      societiesData.forEach(soc => {
        socSelect.innerHTML += `<option value="${soc.id}">${soc.name}</option>`;
      });
    } catch (e) {
      console.error("Failed to load societies", e);
    }
  }

  async function showSocietyType() {
    const socId = document.getElementById('societySelect').value;
    const info = document.getElementById('societyInfo');
    const text = document.getElementById('societyTypeText');
    const unitLabel = document.getElementById('unitSelectLabel');
    
    // Reset views
    document.getElementById('flatStructure').style.display = 'none';
    document.getElementById('blockStructure').style.display = 'none';
    document.getElementById('unitSelect').innerHTML = '<option value="">Select Number</option>';

    if (!socId) {
      info.style.display = 'none';
      return;
    }

    const soc = societiesData.find(s => s.id == socId);
    if (soc) {
      info.style.display = 'flex';
      const typeStr = soc.type === 'flat' ? 'Flat Society' : 'Row House Society';
      text.innerText = `This is a ${typeStr}`;
      unitLabel.innerText = soc.type === 'flat' ? 'Flat Number *' : 'House Number *';

      // Load Buildings/Blocks
      const targetSelect = soc.type === 'flat' ? 'towerSelect' : 'blockSelect';
      if (soc.type === 'flat') {
        document.getElementById('flatStructure').style.display = 'block';
      } else {
        document.getElementById('blockStructure').style.display = 'block';
      }
      
      const response = await fetch(`/api/society/${socId}/buildings`);
      const buildings = await response.json();
      const select = document.getElementById(targetSelect);
      select.innerHTML = `<option value="">Select ${soc.type === 'flat' ? 'Tower' : 'Block'}</option>`;
      buildings.forEach(b => {
        select.innerHTML += `<option value="${b.id}">${b.name}</option>`;
      });
    }
  }

  async function loadFloors() {
    const buildingId = document.getElementById('towerSelect').value;
    const floorSelect = document.getElementById('floorSelect');
    floorSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (!buildingId) {
       floorSelect.innerHTML = '<option value="">Select Floor</option>';
       return;
    }

    const response = await fetch(`/api/building/${buildingId}/floors`);
    const floors = await response.json();
    floorSelect.innerHTML = '<option value="">Select Floor</option>';
    floors.forEach(f => {
      floorSelect.innerHTML += `<option value="${f}">${f} Floor</option>`;
    });
  }

  async function loadUnits(type) {
    const buildingId = (type === 'flat') ? document.getElementById('towerSelect').value : document.getElementById('blockSelect').value;
    const floor = (type === 'flat') ? document.getElementById('floorSelect').value : '';
    const unitSelect = document.getElementById('unitSelect');
    
    unitSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (!buildingId) {
      unitSelect.innerHTML = '<option value="">Select Number</option>';
      return;
    }

    let url = `/api/building/${buildingId}/units`;
    if (floor) url += `?floor=${floor}`;
    
    const response = await fetch(url);
    const units = await response.json();
    unitSelect.innerHTML = '<option value="">Select Number</option>';
    units.forEach(u => {
      unitSelect.innerHTML += `<option value="${u.unit_number}">${u.unit_number}</option>`;
    });
  }

  function togglePassword(id, iconId) {
    const field = document.getElementById(id);
    const icon = document.getElementById(iconId);
    if (field.type === 'password') {
      field.type = 'text';
      icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
      field.type = 'password';
      icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
  }
</script>
  </div>
</div>
</body>
</html>
