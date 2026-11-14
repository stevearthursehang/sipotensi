/**
 * SIPOTENSI SANGIHE - 3D Perspective & WebGL Digital Twin Engine
 * Features:
 * 1. Leaflet Map 3D Perspective Tilt & HUD Controller
 * 2. Three.js WebGL Interactive 3D Island & Masterplan Digital Twin
 */

let is3DModeActive = false;
let currentPitch = 48;
let currentBearing = -6;
let isAutoOrbiting = false;
let orbitAngle = -6;
let orbitDirection = 1;
let orbitAnimationId = null;

// Three.js Globals
let threeScene = null;
let threeCamera = null;
let threeRenderer = null;
let threeControls = null;
let threeAnimId = null;
let oceanMesh = null;
let bubbleParticles = null;
let interactiveObjects = [];
let hoveredObject = null;
let raycaster = null;
let mouse = null;
let current3DTheme = 'realistic';
let isModalAutoRotating = true;
let isCameraTransitioning = false;
let camTargetPos = null;
let camTargetLook = null;

/**
 * Toggle Leaflet 3D Perspective View
 */
function toggle3DExperience(btn) {
    is3DModeActive = !is3DModeActive;
    const wrapper = document.querySelector('.map-wrapper');
    const hud = document.getElementById('hud3DController');
    const dockBtn = document.getElementById('btn3DMode');

    if (is3DModeActive) {
        if (wrapper) {
            wrapper.classList.add('mode-3d-active');
            wrapper.style.setProperty('--map-pitch', currentPitch + 'deg');
            wrapper.style.setProperty('--map-bearing', currentBearing + 'deg');
        }
        if (hud) hud.classList.remove('d-none');
        if (dockBtn) dockBtn.classList.add('btn-3d-active');

        // If currently on OSM street map, auto-switch to Satellite HD for maximum 3D realism
        if (typeof switchBasemap === 'function' && typeof currentBaseLayer !== 'undefined' && currentBaseLayer === osmLayer) {
            const satBtn = document.querySelector('.dock-btn[data-basemap="satellite"]');
            switchBasemap('satellite', satBtn);
        }

        setTimeout(() => { if (typeof map !== 'undefined') map.invalidateSize(); }, 300);
    } else {
        exit3DMode();
    }
}

function update3DPitch(val) {
    currentPitch = parseInt(val, 10);
    const wrapper = document.querySelector('.map-wrapper');
    const lbl = document.getElementById('lblPitchVal');
    if (wrapper) wrapper.style.setProperty('--map-pitch', currentPitch + 'deg');
    if (lbl) lbl.textContent = currentPitch + '°';
}

function update3DBearing(val) {
    currentBearing = parseInt(val, 10);
    const wrapper = document.querySelector('.map-wrapper');
    const lbl = document.getElementById('lblBearingVal');
    if (wrapper) wrapper.style.setProperty('--map-bearing', currentBearing + 'deg');
    if (lbl) lbl.textContent = currentBearing + '°';
}

function toggleAutoOrbit() {
    isAutoOrbiting = !isAutoOrbiting;
    const btn = document.getElementById('btnAutoOrbit');

    if (isAutoOrbiting) {
        if (btn) {
            btn.classList.remove('btn-outline-warning');
            btn.classList.add('btn-warning', 'text-dark', 'fw-bold');
        }
        runOrbitLoop();
    } else {
        if (btn) {
            btn.classList.remove('btn-warning', 'text-dark', 'fw-bold');
            btn.classList.add('btn-outline-warning');
        }
        if (orbitAnimationId) cancelAnimationFrame(orbitAnimationId);
    }
}

function runOrbitLoop() {
    if (!isAutoOrbiting || !is3DModeActive) return;

    orbitAngle += 0.18 * orbitDirection;
    if (orbitAngle > 28) orbitDirection = -1;
    if (orbitAngle < -28) orbitDirection = 1;

    update3DBearing(Math.round(orbitAngle));
    const slider = document.getElementById('sliderBearing');
    if (slider) slider.value = Math.round(orbitAngle);

    orbitAnimationId = requestAnimationFrame(runOrbitLoop);
}

function exit3DMode() {
    is3DModeActive = false;
    isAutoOrbiting = false;
    if (orbitAnimationId) cancelAnimationFrame(orbitAnimationId);

    const wrapper = document.querySelector('.map-wrapper');
    const hud = document.getElementById('hud3DController');
    const dockBtn = document.getElementById('btn3DMode');
    const orbitBtn = document.getElementById('btnAutoOrbit');

    if (wrapper) {
        wrapper.classList.remove('mode-3d-active');
        wrapper.style.removeProperty('--map-pitch');
        wrapper.style.removeProperty('--map-bearing');
    }
    if (hud) hud.classList.add('d-none');
    if (dockBtn) dockBtn.classList.remove('btn-3d-active');
    if (orbitBtn) {
        orbitBtn.classList.remove('btn-warning', 'text-dark', 'fw-bold');
        orbitBtn.classList.add('btn-outline-warning');
    }

    setTimeout(() => { if (typeof map !== 'undefined') map.invalidateSize(); }, 350);
}

/**
 * Open Digital Twin WebGL Modal
 */
function openDigitalTwinModal() {
    const modalEl = document.getElementById('modalDigitalTwin');
    if (!modalEl || typeof bootstrap === 'undefined') return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();

    setTimeout(() => {
        initDigitalTwinThreeJS();
    }, 200);
}

function openDigitalTwinForProject(name, sector, capex, roi, pdfUrl) {
    openDigitalTwinModal();

    setTimeout(() => {
        // Find matching project
        const lower = (name || '').toLowerCase();
        let targetKey = 'all';
        if (lower.includes('pelabuhan') || lower.includes('tahuna')) targetKey = 'tahuna';
        else if (lower.includes('dagho') || lower.includes('ikan') || lower.includes('perikanan')) targetKey = 'dagho';
        else if (lower.includes('bukide') || lower.includes('pantai') || lower.includes('resort')) targetKey = 'bukide';
        else if (lower.includes('gunung') || lower.includes('mahangetang') || lower.includes('bawah laut')) targetKey = 'mahangetang';
        else if (lower.includes('pala') || lower.includes('solar') || lower.includes('energi')) targetKey = 'solar';

        focus3DCamera(targetKey);

        show3DProjectCard({
            nama: name,
            sektor: sector || 'Investasi Unggulan',
            lokasi: 'Kabupaten Kepulauan Sangihe',
            capex: capex || 'Rp -',
            roi: roi ? (roi + '% / Tahun') : 'Prospektif',
            pdf: pdfUrl || '#'
        });
    }, 450);
}

/**
 * Three.js WebGL Interactive Digital Twin Implementation
 */
function initDigitalTwinThreeJS() {
    const container = document.getElementById('threejsContainer');
    if (!container) return;

    // Check if ThreeJS is loaded
    if (typeof THREE === 'undefined') {
        container.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-warning"><i class="bi bi-exclamation-triangle me-2"></i> Pustaka WebGL 3D sedang dimuat...</div>';
        return;
    }

    // If already initialized, just resize
    if (threeRenderer) {
        onWindowResize3D();
        return;
    }

    const width = container.clientWidth || window.innerWidth;
    const height = container.clientHeight || (window.innerHeight - 58);

    // 1. Scene
    threeScene = new THREE.Scene();
    threeScene.background = new THREE.Color(0x0284c7);
    threeScene.fog = new THREE.FogExp2(0x0284c7, 0.0075);

    // 2. Camera
    threeCamera = new THREE.PerspectiveCamera(45, width / height, 0.5, 1500);
    threeCamera.position.set(0, 65, 85);

    // 3. Renderer
    threeRenderer = new THREE.WebGLRenderer({ antialias: true, powerPreference: 'high-performance' });
    threeRenderer.setSize(width, height);
    threeRenderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    threeRenderer.shadowMap.enabled = true;
    threeRenderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.innerHTML = '';
    container.appendChild(threeRenderer.domElement);

    // 4. Orbit Controls
    if (typeof THREE.OrbitControls !== 'undefined') {
        threeControls = new THREE.OrbitControls(threeCamera, threeRenderer.domElement);
        threeControls.enableDamping = true;
        threeControls.dampingFactor = 0.05;
        threeControls.maxPolarAngle = Math.PI / 2.05;
        threeControls.minDistance = 15;
        threeControls.maxDistance = 220;
        threeControls.autoRotate = isModalAutoRotating;
        threeControls.autoRotateSpeed = 0.8;
    }

    // 5. Lights
    setup3DLights();

    // 6. Build Island Geometry (Topography of Kepulauan Sangihe)
    buildSangiheTerrain();

    // 7. Ocean Surface
    buildOceanWater();

    // 8. 3D Investment Projects & Models
    build3DProjectModels();

    // 9. Raycasting Setup
    raycaster = new THREE.Raycaster();
    mouse = new THREE.Vector2();

    container.addEventListener('mousemove', on3DMouseMove);
    container.addEventListener('click', on3DMouseClick);
    window.addEventListener('resize', onWindowResize3D);

    // Clock for delta & animation
    clock = new THREE.Clock();

    // Render loop
    animate3DLoop();
}

let dirLight, hemiLight, ambLight, nightGroup, starMesh;

function setup3DLights() {
    ambLight = new THREE.AmbientLight(0xffffff, 0.55);
    threeScene.add(ambLight);

    hemiLight = new THREE.HemisphereLight(0xbae6fd, 0x14532d, 0.6);
    hemiLight.position.set(0, 100, 0);
    threeScene.add(hemiLight);

    dirLight = new THREE.DirectionalLight(0xfffbeb, 1.35);
    dirLight.position.set(60, 100, 50);
    dirLight.castShadow = true;
    dirLight.shadow.mapSize.width = 1024;
    dirLight.shadow.mapSize.height = 1024;
    dirLight.shadow.camera.near = 10;
    dirLight.shadow.camera.far = 250;
    dirLight.shadow.camera.left = -60;
    dirLight.shadow.camera.right = 60;
    dirLight.shadow.camera.top = 60;
    dirLight.shadow.camera.bottom = -60;
    threeScene.add(dirLight);

    // Night Lights group
    nightGroup = new THREE.Group();
    nightGroup.visible = false;

    // Stars
    const starGeo = new THREE.BufferGeometry();
    const starCount = 800;
    const starPos = new Float32Array(starCount * 3);
    for (let i = 0; i < starCount * 3; i += 3) {
        starPos[i] = (Math.random() - 0.5) * 800;
        starPos[i + 1] = Math.random() * 250 + 20;
        starPos[i + 2] = (Math.random() - 0.5) * 800;
    }
    starGeo.setAttribute('position', new THREE.BufferAttribute(starPos, 3));
    const starMat = new THREE.PointsMaterial({ color: 0xffffff, size: 1.5, transparent: true, opacity: 0.85 });
    starMesh = new THREE.Points(starGeo, starMat);
    nightGroup.add(starMesh);

    // City glow point lights
    const cityGlow = new THREE.PointLight(0xfacc15, 2.5, 35);
    cityGlow.position.set(-4, 5, -2);
    nightGroup.add(cityGlow);

    const portGlow = new THREE.PointLight(0x38bdf8, 2.5, 30);
    portGlow.position.set(-5, 4, 16);
    nightGroup.add(portGlow);

    threeScene.add(nightGroup);
}

let terrainMesh = null;
let cyberGridMesh = null;

function buildSangiheTerrain() {
    // Sangihe Main Island Topography (Elongated North-South shape with volcanic ridges)
    const segments = 90;
    const geo = new THREE.PlaneGeometry(50, 85, segments, segments);
    geo.rotateX(-Math.PI / 2);

    const pos = geo.attributes.position;
    const count = pos.count;
    const colors = new Float32Array(count * 3);

    for (let i = 0; i < count; i++) {
        const x = pos.getX(i);
        const z = pos.getZ(i);

        // Island footprint mask: elongated north-south with bay indentations
        const distNorth = Math.hypot(x - 0, z + 18) / 16;  // Awu Volcano
        const distSouth = Math.hypot(x - 1, z - 16) / 18;  // Sahendaruman Volcano
        const distCenter = Math.hypot(x + 2, z - 0) / 12;   // Tahuna Central ridge
        const distBukide = Math.hypot(x - 10, z + 32) / 6;  // Bukide Isle
        const distMahang = Math.hypot(x + 8, z - 38) / 6;  // Mahangetang Isle

        let elevation = 0;

        // Combine volcanic landmasses
        if (distNorth < 1) {
            elevation = Math.max(elevation, (1 - distNorth) * 16 * (1 + 0.15 * Math.sin(x * 0.8) * Math.cos(z * 0.8)));
        }
        if (distSouth < 1) {
            elevation = Math.max(elevation, (1 - distSouth) * 18 * (1 + 0.12 * Math.cos(x * 0.7) * Math.sin(z * 0.7)));
        }
        if (distCenter < 1) {
            elevation = Math.max(elevation, (1 - distCenter) * 11);
        }
        if (distBukide < 1) {
            elevation = Math.max(elevation, (1 - distBukide) * 6);
        }
        if (distMahang < 1) {
            elevation = Math.max(elevation, (1 - distMahang) * 4.5);
        }

        // Coastal Bay indentation for Teluk Tahuna at (x: -5, z: -2)
        const distBay = Math.hypot(x + 6, z + 2) / 5;
        if (distBay < 1) {
            elevation *= Math.pow(distBay, 1.5);
        }

        // Lowland shoreline cutoff
        if (elevation < 0.6) elevation = -0.5;

        pos.setY(i, elevation);

        // Vertex coloring based on elevation:
        // Sand Shore -> Tropical Rainforest -> High Mountain Ridge -> Volcanic Rock
        let r = 0.08, g = 0.5, b = 0.22; // Lush Green
        if (elevation <= 0) {
            r = 0.02; g = 0.35; b = 0.55; // Sea floor
        } else if (elevation < 1.4) {
            r = 0.88; g = 0.82; b = 0.65; // Sandy Beach
        } else if (elevation < 8.0) {
            r = 0.11; g = 0.55; b = 0.22; // Rainforest
        } else if (elevation < 14.0) {
            r = 0.25; g = 0.45; b = 0.35; // Highland
        } else {
            r = 0.55; g = 0.58; b = 0.62; // Rocky Peak
        }

        colors[i * 3] = r;
        colors[i * 3 + 1] = g;
        colors[i * 3 + 2] = b;
    }

    geo.setAttribute('color', new THREE.BufferAttribute(colors, 3));
    geo.computeVertexNormals();

    const mat = new THREE.MeshStandardMaterial({
        vertexColors: true,
        roughness: 0.85,
        metalness: 0.1,
        flatShading: true
    });

    terrainMesh = new THREE.Mesh(geo, mat);
    terrainMesh.receiveShadow = true;
    terrainMesh.castShadow = true;
    threeScene.add(terrainMesh);

    // Cyberpunk Wireframe Grid overlay (hidden by default)
    const wireMat = new THREE.MeshBasicMaterial({ color: 0x06b6d4, wireframe: true, transparent: true, opacity: 0.45 });
    cyberGridMesh = new THREE.Mesh(geo, wireMat);
    cyberGridMesh.position.y = 0.08;
    cyberGridMesh.visible = false;
    threeScene.add(cyberGridMesh);
}

function buildOceanWater() {
    const oceanGeo = new THREE.PlaneGeometry(320, 320, 48, 48);
    oceanGeo.rotateX(-Math.PI / 2);

    const oceanMat = new THREE.MeshStandardMaterial({
        color: 0x0284c7,
        transparent: true,
        opacity: 0.78,
        roughness: 0.15,
        metalness: 0.35
    });

    oceanMesh = new THREE.Mesh(oceanGeo, oceanMat);
    oceanMesh.position.y = 0;
    oceanMesh.receiveShadow = true;
    threeScene.add(oceanMesh);
}

function build3DProjectModels() {
    // 1. Pelabuhan Samudera Tahuna (X: -6, Z: -2)
    const tahunaGroup = new THREE.Group();
    tahunaGroup.position.set(-6, 0.4, -2);

    // Concrete Pier
    const pierMat = new THREE.MeshStandardMaterial({ color: 0x94a3b8, roughness: 0.6 });
    const pier = new THREE.Mesh(new THREE.BoxGeometry(10, 0.7, 3.5), pierMat);
    pier.castShadow = true;
    tahunaGroup.add(pier);

    // Terminal Building
    const termMat = new THREE.MeshStandardMaterial({ color: 0x2563eb, metalness: 0.4, roughness: 0.3 });
    const terminal = new THREE.Mesh(new THREE.BoxGeometry(4, 2, 2.5), termMat);
    terminal.position.set(-2, 1.2, 0);
    terminal.castShadow = true;
    tahunaGroup.add(terminal);

    // Cargo Ship
    const shipMat = new THREE.MeshStandardMaterial({ color: 0xd97706, roughness: 0.5 });
    const ship = new THREE.Mesh(new THREE.BoxGeometry(6.5, 1.4, 2), shipMat);
    ship.position.set(2, 0.3, -3.2);
    tahunaGroup.add(ship);

    // Gantry Crane
    const craneMat = new THREE.MeshStandardMaterial({ color: 0xef4444, metalness: 0.6 });
    const crane = new THREE.Mesh(new THREE.CylinderGeometry(0.2, 0.2, 4, 6), craneMat);
    crane.position.set(1.5, 2, 0.5);
    tahunaGroup.add(crane);

    // Beacon & Hotspot
    const isEnMode = (typeof window.i18n !== 'undefined' && window.i18n.isEn) || (document.documentElement.lang === 'en');
    const bilText = isEnMode ? 'Billion' : 'Miliar';
    const tahunaTitle = isEnMode ? 'Tahuna Ocean Port' : 'Pelabuhan Samudera Tahuna';
    const tahunaSector = isEnMode ? 'Infrastructure & Maritime' : 'Infrastruktur & Maritim';
    const daghoTitle = isEnMode ? 'Dagho Integrated Fishery Center' : 'Sentra Perikanan Terpadu Dagho';
    const daghoSector = isEnMode ? 'Fisheries & Marine' : 'Perikanan & Kelautan';
    const bukideTitle = isEnMode ? 'Bukide Marine Tourism Resort' : 'Resort Wisata Bahari Bukide';
    const bukideSector = isEnMode ? 'Tourism & Hospitality' : 'Pariwisata & Hospitaliti';
    const mahangTitle = isEnMode ? 'Mahangetang Underwater Volcano Tourism' : 'Wisata Gunung Api Bawah Laut Mahangetang';
    const mahangSector = isEnMode ? 'Specialty Tourism & Diving' : 'Pariwisata Khusus & Diving';

    createProjectBeacon(tahunaGroup, tahunaTitle, tahunaSector, `Rp 200 ${bilText}`, '18.5%', '/potensi/30/pdf', 'tahuna');
    threeScene.add(tahunaGroup);

    // 2. Sentra Hilirisasi Perikanan Dagho (X: -6, Z: 18)
    const daghoGroup = new THREE.Group();
    daghoGroup.position.set(-6, 0.4, 18);

    const warehouseMat = new THREE.MeshStandardMaterial({ color: 0x10b981, roughness: 0.4 });
    const wh1 = new THREE.Mesh(new THREE.BoxGeometry(6, 2, 3), warehouseMat);
    wh1.position.set(0, 1, 0);
    daghoGroup.add(wh1);

    const siloMat = new THREE.MeshStandardMaterial({ color: 0xcbd5e1, metalness: 0.7 });
    const silo1 = new THREE.Mesh(new THREE.CylinderGeometry(1.2, 1.2, 3, 16), siloMat);
    silo1.position.set(4, 1.5, 0.5);
    daghoGroup.add(silo1);

    createProjectBeacon(daghoGroup, daghoTitle, daghoSector, `Rp 45 ${bilText}`, '22.0%', '/potensi/30/pdf', 'dagho');
    threeScene.add(daghoGroup);

    // 3. Kawasan Wisata Bahari Bukide (X: 10, Z: -32)
    const bukideGroup = new THREE.Group();
    bukideGroup.position.set(10, 0.3, -32);

    const woodMat = new THREE.MeshStandardMaterial({ color: 0xb45309, roughness: 0.8 });
    const boardwalk = new THREE.Mesh(new THREE.BoxGeometry(8, 0.3, 1.2), woodMat);
    bukideGroup.add(boardwalk);

    // 3 Overwater Villas
    const roofMat = new THREE.MeshStandardMaterial({ color: 0xf59e0b, roughness: 0.9 });
    for (let k = -2; k <= 2; k += 2) {
        const villa = new THREE.Mesh(new THREE.ConeGeometry(1.3, 1.5, 5), roofMat);
        villa.position.set(k * 1.8, 1.4, (k % 2 === 0 ? 1.5 : -1.5));
        bukideGroup.add(villa);
    }

    createProjectBeacon(bukideGroup, bukideTitle, bukideSector, `Rp 12 ${bilText}`, '19.0%', '/potensi/30/pdf', 'bukide');
    threeScene.add(bukideGroup);

    // 4. Gunung Api Bawah Laut Mahangetang (X: 8, Z: -38)
    const mahangGroup = new THREE.Group();
    mahangGroup.position.set(8, -0.8, 38);

    const volcanoMat = new THREE.MeshStandardMaterial({ color: 0x7c2d12, roughness: 0.9, flatShading: true });
    const cone = new THREE.Mesh(new THREE.CylinderGeometry(1.5, 5, 3.5, 12, 1, true), volcanoMat);
    mahangGroup.add(cone);

    // Animated Geothermal Bubbles
    const bubbleGeo = new THREE.BufferGeometry();
    const bCount = 60;
    const bPos = new Float32Array(bCount * 3);
    for (let i = 0; i < bCount * 3; i += 3) {
        bPos[i] = (Math.random() - 0.5) * 3;
        bPos[i + 1] = Math.random() * 4.5;
        bPos[i + 2] = (Math.random() - 0.5) * 3;
    }
    bubbleGeo.setAttribute('position', new THREE.BufferAttribute(bPos, 3));
    const bMat = new THREE.PointsMaterial({ color: 0x38bdf8, size: 0.45, transparent: true, opacity: 0.8 });
    bubbleParticles = new THREE.Points(bubbleGeo, bMat);
    mahangGroup.add(bubbleParticles);

    createProjectBeacon(mahangGroup, mahangTitle, mahangSector, `Rp 25 ${bilText}`, '24.5%', '/potensi/30/pdf', 'mahangetang');
    threeScene.add(mahangGroup);
}

function createProjectBeacon(group, name, sector, capex, roi, pdf, key) {
    // Holographic Crystal Beacon
    const beaconGeo = new THREE.OctahedronGeometry(1.1, 0);
    const beaconMat = new THREE.MeshStandardMaterial({
        color: 0x38bdf8,
        emissive: 0x0284c7,
        emissiveIntensity: 0.8,
        metalness: 0.9,
        roughness: 0.1
    });
    const beacon = new THREE.Mesh(beaconGeo, beaconMat);
    beacon.position.y = 5.5;
    beacon.castShadow = true;

    // Glowing Ground Sonar Ring
    const ringGeo = new THREE.RingGeometry(1.2, 1.8, 24);
    ringGeo.rotateX(-Math.PI / 2);
    const ringMat = new THREE.MeshBasicMaterial({ color: 0x38bdf8, side: THREE.DoubleSide, transparent: true, opacity: 0.65 });
    const ring = new THREE.Mesh(ringGeo, ringMat);
    ring.position.y = 0.15;

    group.add(beacon);
    group.add(ring);

    // Attach metadata for raycasting interaction
    beacon.userData = {
        name: name,
        sector: sector,
        capex: capex,
        roi: roi,
        pdf: pdf,
        key: key,
        groupPos: group.position,
        beaconMesh: beacon,
        ringMesh: ring
    };

    interactiveObjects.push(beacon);
}

function on3DMouseMove(e) {
    const container = document.getElementById('threejsContainer');
    if (!container || !threeCamera) return;

    const rect = container.getBoundingClientRect();
    mouse.x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
    mouse.y = -((e.clientY - rect.top) / rect.height) * 2 + 1;

    raycaster.setFromCamera(mouse, threeCamera);
    const intersects = raycaster.intersectObjects(interactiveObjects);

    if (intersects.length > 0) {
        const obj = intersects[0].object;
        if (hoveredObject !== obj) {
            if (hoveredObject) hoveredObject.scale.set(1, 1, 1);
            hoveredObject = obj;
            hoveredObject.scale.set(1.4, 1.4, 1.4);
            container.style.cursor = 'pointer';
        }
    } else {
        if (hoveredObject) {
            hoveredObject.scale.set(1, 1, 1);
            hoveredObject = null;
            container.style.cursor = 'grab';
        }
    }
}

function on3DMouseClick(e) {
    if (!hoveredObject) return;
    const data = hoveredObject.userData;
    if (!data) return;

    // Focus camera
    focus3DCamera(data.key);

    // Show card info
    const isEn = (typeof window.i18n !== 'undefined' && window.i18n.isEn) || (document.documentElement.lang === 'en');
    show3DProjectCard({
        nama: data.name,
        sektor: data.sector,
        lokasi: isEn ? 'Sangihe Islands Regency' : 'Kabupaten Kepulauan Sangihe',
        capex: data.capex,
        roi: data.roi,
        pdf: data.pdf
    });
}

function show3DProjectCard(data) {
    const card = document.getElementById('card3DProjectInfo');
    if (!card) return;

    document.getElementById('title3DProject').textContent = data.nama;
    document.getElementById('badge3DSector').textContent = data.sektor;
    document.getElementById('capex3DProject').textContent = data.capex;
    document.getElementById('roi3DProject').textContent = data.roi;
    document.getElementById('link3DProspectus').href = data.pdf;

    card.classList.remove('d-none');
}

function close3DProjectCard() {
    const card = document.getElementById('card3DProjectInfo');
    if (card) card.classList.add('d-none');
}

function focus3DCamera(targetKey) {
    if (!threeControls || !threeCamera) return;

    const targets = {
        all: { pos: new THREE.Vector3(0, 65, 85), look: new THREE.Vector3(0, 0, 0) },
        tahuna: { pos: new THREE.Vector3(-15, 14, 8), look: new THREE.Vector3(-6, 2, -2) },
        dagho: { pos: new THREE.Vector3(-14, 15, 30), look: new THREE.Vector3(-6, 2, 18) },
        bukide: { pos: new THREE.Vector3(18, 14, -20), look: new THREE.Vector3(10, 2, -32) },
        mahangetang: { pos: new THREE.Vector3(4, 12, 50), look: new THREE.Vector3(8, -0.5, 38) }
    };

    const t = targets[targetKey] || targets.all;
    camTargetPos = t.pos;
    camTargetLook = t.look;
    isCameraTransitioning = true;
}

function set3DStyle(style) {
    current3DTheme = style;

    // UI Buttons active toggle
    ['btnStyleRealistic', 'btnStyleNight', 'btnStyleCyber'].forEach(id => {
        const b = document.getElementById(id);
        if (b) b.classList.remove('active');
    });

    if (style === 'realistic') {
        const b = document.getElementById('btnStyleRealistic');
        if (b) b.classList.add('active');
        threeScene.background.setHex(0x0284c7);
        threeScene.fog.color.setHex(0x0284c7);
        dirLight.intensity = 1.35;
        ambLight.intensity = 0.55;
        nightGroup.visible = false;
        if (terrainMesh) terrainMesh.visible = true;
        if (cyberGridMesh) cyberGridMesh.visible = false;
        if (oceanMesh) {
            oceanMesh.material.color.setHex(0x0284c7);
            oceanMesh.material.wireframe = false;
        }
    } else if (style === 'night') {
        const b = document.getElementById('btnStyleNight');
        if (b) b.classList.add('active');
        threeScene.background.setHex(0x030712);
        threeScene.fog.color.setHex(0x030712);
        dirLight.intensity = 0.15;
        ambLight.intensity = 0.2;
        nightGroup.visible = true;
        if (terrainMesh) terrainMesh.visible = true;
        if (cyberGridMesh) cyberGridMesh.visible = false;
        if (oceanMesh) {
            oceanMesh.material.color.setHex(0x07152d);
            oceanMesh.material.wireframe = false;
        }
    } else if (style === 'cyber') {
        const b = document.getElementById('btnStyleCyber');
        if (b) b.classList.add('active');
        threeScene.background.setHex(0x020617);
        threeScene.fog.color.setHex(0x020617);
        dirLight.intensity = 0.5;
        ambLight.intensity = 0.4;
        nightGroup.visible = true;
        if (terrainMesh) terrainMesh.visible = true;
        if (cyberGridMesh) cyberGridMesh.visible = true;
        if (oceanMesh) {
            oceanMesh.material.color.setHex(0x0f172a);
            oceanMesh.material.wireframe = true;
        }
    }
}

function toggleModal3DRotation() {
    isModalAutoRotating = !isModalAutoRotating;
    const btn = document.getElementById('btnModalAutoRotate');
    if (threeControls) threeControls.autoRotate = isModalAutoRotating;

    if (btn) {
        if (isModalAutoRotating) {
            btn.classList.add('btn-warning');
            btn.classList.remove('btn-outline-warning');
        } else {
            btn.classList.remove('btn-warning');
            btn.classList.add('btn-outline-warning');
        }
    }
}

function onWindowResize3D() {
    const container = document.getElementById('threejsContainer');
    if (!container || !threeCamera || !threeRenderer) return;

    const width = container.clientWidth || window.innerWidth;
    const height = container.clientHeight || (window.innerHeight - 58);

    threeCamera.aspect = width / height;
    threeCamera.updateProjectionMatrix();
    threeRenderer.setSize(width, height);
}

let clock = null;

function animate3DLoop() {
    threeAnimId = requestAnimationFrame(animate3DLoop);

    const time = clock ? clock.getElapsedTime() : Date.now() * 0.001;

    // Ocean waves animation
    if (oceanMesh && oceanMesh.geometry) {
        const pos = oceanMesh.geometry.attributes.position;
        for (let i = 0; i < pos.count; i++) {
            const u = pos.getX(i);
            const v = pos.getZ(i);
            pos.setY(i, Math.sin(time * 1.5 + u * 0.1) * Math.cos(time * 1.2 + v * 0.1) * 0.25);
        }
        oceanMesh.geometry.attributes.position.needsUpdate = true;
    }

    // Geothermal bubble animation
    if (bubbleParticles && bubbleParticles.geometry) {
        const bPos = bubbleParticles.geometry.attributes.position;
        for (let i = 1; i < bPos.count * 3; i += 3) {
            bPos.array[i] += 0.05;
            if (bPos.array[i] > 5) bPos.array[i] = 0;
        }
        bubbleParticles.geometry.attributes.position.needsUpdate = true;
    }

    // Beacons floating animation
    interactiveObjects.forEach((beacon, idx) => {
        beacon.rotation.y += 0.025;
        beacon.position.y = 5.2 + Math.sin(time * 2.5 + idx) * 0.45;
    });

    // Camera smooth transition
    if (isCameraTransitioning && camTargetPos && camTargetLook && threeControls) {
        threeCamera.position.lerp(camTargetPos, 0.06);
        threeControls.target.lerp(camTargetLook, 0.06);
        if (threeCamera.position.distanceTo(camTargetPos) < 0.2) {
            isCameraTransitioning = false;
        }
    }

    if (threeControls) threeControls.update();
    if (threeRenderer && threeScene && threeCamera) {
        threeRenderer.render(threeScene, threeCamera);
    }
}

// Auto-trigger 3D mode if URL has view=3d or mode3d
window.addEventListener('load', function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('view') === '3d' || params.has('mode3d')) {
        setTimeout(function() {
            if (typeof toggle3DExperience === 'function' && !is3DModeActive) {
                toggle3DExperience();
            }
        }, 1200);
    }
});
