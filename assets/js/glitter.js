(() => {
  if (!document.querySelector('.boxes')) return

  const VERT = `
attribute vec2 aPos;
varying vec2 vUv;
void main() {
  vUv = vec2(aPos.x * 0.5 + 0.5, 0.5 - aPos.y * 0.5);
  gl_Position = vec4(aPos, 0.0, 1.0);
}
`

  const FRAG = `
#ifdef GL_FRAGMENT_PRECISION_HIGH
precision highp float;
#else
precision mediump float;
#endif

varying vec2 vUv;
uniform sampler2D uTex;
uniform vec2 uRes;
uniform float uScale;
uniform float uFrame;

const vec3 LUMA = vec3(0.2126, 0.7152, 0.0722);
const vec3 PINK = vec3(0.780, 0.333, 0.757);
const vec3 GREY0 = vec3(0.322, 0.333, 0.329);
const vec3 GREY1 = vec3(0.612, 0.627, 0.612);
const vec3 GREY2 = vec3(0.835, 0.859, 0.831);
const vec3 GREY3 = vec3(0.933, 0.949, 0.925);

float hash12(vec2 p) {
  vec3 p3 = fract(vec3(p.xyx) * 0.1031);
  p3 += dot(p3, p3.yzx + 33.33);
  return fract((p3.x + p3.y) * p3.z);
}

vec3 hash32(vec2 p) {
  vec3 p3 = fract(vec3(p.xyx) * vec3(0.1031, 0.1030, 0.0973));
  p3 += dot(p3, p3.yxz + 33.33);
  return fract((p3.xxy + p3.yzz) * p3.zyx);
}

float taper(float x) {
  x = max(1.0 - x, 0.0);
  return x * x;
}

vec3 greys(float y) {
  float s = (y - 0.44) / 0.56;
  float u = sign(s) * pow(abs(s), 0.75);
  return u < 0.0 ? mix(GREY1, GREY0, -u / 0.8345)
    : u < 0.6552 ? mix(GREY1, GREY2, u / 0.6552)
    : mix(GREY2, GREY3, (u - 0.6552) / 0.3448);
}

vec3 tintOf(vec3 c) {
  vec3 d = c - dot(c, LUMA);
  float m = length(d);
  d *= min(m * 3.0, 0.38) / max(m, 0.0001);
  return 1.0 + d - max(d.r, max(d.g, d.b));
}

vec3 sparkles(vec2 p, float cell, float seed, float r0, float r1, float bias, float density, vec3 tint) {
  vec2 c0 = floor(p / cell);
  float reach = cell * 1.15;
  vec3 acc = vec3(0.0);
  for (int j = -1; j <= 1; j++) {
    for (int i = -1; i <= 1; i++) {
      vec2 id = c0 + vec2(float(i), float(j));
      float on = smoothstep(1.0 - density, 1.0, hash12(id * 1.37 + seed + uFrame * 17.31));
      if (on <= 0.0) continue;
      vec3 h = hash32(id + seed);
      vec2 d = p - floor((id + 0.15 + 0.7 * h.xy) * cell) - 0.5;
      float dd = dot(d, d);
      if (dd > reach * reach) continue;
      float base = mix(r0, r1, pow(h.z, bias));
      float sz = clamp((base - 0.2) / 0.9, 0.0, 1.0);
      float rad = base * uScale * 0.9 * (0.7 + 0.3 * on);
      float th = rad * 0.2 + 0.3;
      float len = rad * mix(1.2, 5.0, sz);
      vec2 ad = abs(d);
      vec2 dg = abs(vec2(d.x + d.y, d.x - d.y)) * 0.7071;
      float rays = taper(ad.x / len) * exp(-ad.y / th) + taper(ad.y / len) * exp(-ad.x / th);
      float diag = taper(dg.x / (len * 0.45)) * exp(-dg.y / th) + taper(dg.y / (len * 0.45)) * exp(-dg.x / th);
      float core = exp(-dd / (rad * rad));
      float shape = core
        + rays * 0.85 * smoothstep(0.1, 0.8, sz)
        + diag * 0.35 * smoothstep(0.55, 1.0, sz)
        + exp(-dd / (rad * rad * 6.0)) * 0.3 * sz;
      vec3 h2 = hash32(id * 2.17 + seed + 5.0);
      vec3 col = h2.x < 0.7 ? tint : 0.65 + 0.35 * cos(6.2832 * (h2.y + uFrame * 0.37 + vec3(0.0, 0.33, 0.67)));
      acc += mix(col, vec3(1.0), clamp(core * 0.8, 0.0, 1.0)) * on * shape;
    }
  }
  return acc;
}

void main() {
  vec2 px = vUv * uRes;
  vec2 one = vec2(uScale) / uRes;

  float y = clamp(dot(texture2D(uTex, vUv).rgb, LUMA), 0.0, 1.0);
  float pm = 1.0 - clamp((y - 0.42) / 0.04, 0.0, 1.0);
  vec3 g = mix(greys(y), PINK, pm);

  float halo = 0.0;
  for (int i = 0; i < 8; i++) {
    float a = float(i) * 0.7854 + 0.39;
    vec2 o = vec2(cos(a), sin(a)) * one;
    halo += max(dot(texture2D(uTex, vUv + o * 5.0).rgb, LUMA) - 0.7, 0.0) * 0.6;
    halo += max(dot(texture2D(uTex, vUv + o * 12.0).rgb, LUMA) - 0.7, 0.0) * 0.4;
  }
  g = clamp(g + halo / 8.0 * vec3(0.95, 0.84, 0.70) * 0.4 * (1.0 - 0.8 * y), 0.0, 1.0);

  float gw = pm * (1.0 + 0.6 * pm);
  if (gw > 0.0) {
    float gy = dot(g, LUMA);
    vec3 tint = tintOf(g);

    float sv = hash12(floor(px / max(uScale, 1.0)) * 1.13 + uFrame * 13.17 + 3.3);
    float deep = 0.2 * (1.0 - smoothstep(0.62, 0.9, gy));
    vec3 seq = sv < 0.25 ? g * (1.0 - deep)
      : sv < 0.6 ? g
      : sv < 0.88 ? mix(g, tint, 0.3)
      : sv < 0.975 ? mix(g, tint, 0.6)
      : mix(g, vec3(1.0), 0.85);
    g = mix(g, seq, clamp(0.306 * gw, 0.0, 1.0));

    vec2 sp = floor(px) + 0.5;
    vec3 spk = sparkles(sp, uScale * 2.6, 1.7, 0.2, 0.62, 1.5, 0.22, tint)
             + sparkles(sp, uScale * 7.0, 9.3, 0.5, 1.1, 2.6, 0.3, tint);
    vec3 s = clamp(spk * 1.1 * gw * (0.55 + 0.6 * gy), 0.0, 1.0);
    g = 1.0 - (1.0 - g) * (1.0 - s);
  }

  gl_FragColor = vec4(g, 1.0);
}
`

  const canvas = document.createElement('canvas')
  canvas.className = 'glitter'
  canvas.setAttribute('aria-hidden', 'true')
  const gl = canvas.getContext('webgl', {
    alpha: false,
    antialias: false,
    depth: false,
    stencil: false,
    powerPreference: 'low-power'
  })
  if (!gl) return

  const compile = (type, source) => {
    const shader = gl.createShader(type)
    gl.shaderSource(shader, source)
    gl.compileShader(shader)
    return gl.getShaderParameter(shader, gl.COMPILE_STATUS) ? shader : null
  }
  const vs = compile(gl.VERTEX_SHADER, VERT)
  const fs = compile(gl.FRAGMENT_SHADER, FRAG)
  if (!vs || !fs) return
  const program = gl.createProgram()
  gl.attachShader(program, vs)
  gl.attachShader(program, fs)
  gl.linkProgram(program)
  if (!gl.getProgramParameter(program, gl.LINK_STATUS)) return
  gl.useProgram(program)

  gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer())
  gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW)
  const aPos = gl.getAttribLocation(program, 'aPos')
  gl.enableVertexAttribArray(aPos)
  gl.vertexAttribPointer(aPos, 2, gl.FLOAT, false, 0, 0)

  gl.bindTexture(gl.TEXTURE_2D, gl.createTexture())
  gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR)
  gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR)
  gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE)
  gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE)

  const uRes = gl.getUniformLocation(program, 'uRes')
  const uScale = gl.getUniformLocation(program, 'uScale')
  const uFrame = gl.getUniformLocation(program, 'uFrame')

  const scratch = document.createElement('canvas')
  const scratchCtx = scratch.getContext('2d')
  let loaded = ''

  const renderScale = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--render-scale')) || 1
  const frontScale = 1.25 / renderScale
  const still = window.matchMedia('(prefers-reduced-motion: reduce)').matches
  const start = performance.now()

  let hoverBox = null
  let pressBox = null
  let current = null
  let drawn = -1
  let raf = 0

  const attach = box => {
    const img = box.querySelector('img')
    if (!img) return false
    if (!img.complete || !img.naturalWidth) {
      img.addEventListener('load', refresh, { once: true })
      return false
    }
    const dpr = Math.min(window.devicePixelRatio || 1, 2)
    const width = Math.max(2, Math.min(2048, Math.round(img.offsetWidth * frontScale * dpr)))
    const height = Math.max(2, Math.min(2560, Math.round(img.offsetHeight * frontScale * dpr)))
    const key = (img.currentSrc || img.src) + '@' + width + 'x' + height
    if (key !== loaded) {
      scratch.width = width
      scratch.height = height
      scratchCtx.imageSmoothingQuality = 'high'
      scratchCtx.drawImage(img, 0, 0, width, height)
      gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, scratch)
      loaded = key
    }
    canvas.width = width
    canvas.height = height
    gl.viewport(0, 0, width, height)
    gl.uniform2f(uRes, width, height)
    gl.uniform1f(uScale, dpr)
    img.parentElement.appendChild(canvas)
    drawn = -1
    return true
  }

  function update (now) {
    raf = 0
    if (gl.isContextLost()) {
      canvas.remove()
      return
    }
    const want = hoverBox || pressBox
    if (want !== current) {
      current = want && attach(want) ? want : null
      if (!current) canvas.remove()
    }
    if (!current) return
    const frame = still ? 0 : Math.floor((now - start) / 100) % 3
    if (frame !== drawn) {
      drawn = frame
      gl.uniform1f(uFrame, frame)
      gl.drawArrays(gl.TRIANGLES, 0, 3)
    }
    if (!still) raf = requestAnimationFrame(update)
  }

  function refresh () {
    cancelAnimationFrame(raf)
    update(performance.now())
  }

  const boxAt = e => e.target.closest ? e.target.closest('.box') : null

  document.addEventListener('pointermove', e => {
    if (e.pointerType !== 'mouse' || boxAt(e) === hoverBox) return
    hoverBox = boxAt(e)
    refresh()
  }, { passive: true })

  document.addEventListener('pointerdown', e => {
    if (e.pointerType === 'mouse') return
    pressBox = boxAt(e)
    refresh()
  }, { passive: true })

  const release = e => {
    if (e.pointerType === 'mouse') return
    setTimeout(() => {
      pressBox = null
      refresh()
    }, 220)
  }
  document.addEventListener('pointerup', release, { passive: true })
  document.addEventListener('pointercancel', release, { passive: true })

  const leave = () => {
    hoverBox = null
    refresh()
  }
  document.documentElement.addEventListener('pointerleave', leave)
  window.addEventListener('blur', leave)

  window.addEventListener('resize', () => {
    current = null
    refresh()
  })
})()
