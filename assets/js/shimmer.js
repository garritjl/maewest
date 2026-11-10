(() => {
  if (!document.querySelector('.boxes')) return

  const SPEED = 0.86
  const FRAME = 100
  const LOOP = 1024

  const SHARED = `
const vec3 LUMA = vec3(0.2126, 0.7152, 0.0722);
const float LOOP = 1024.0;

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

vec3 tintOf(vec3 c) {
  vec3 d = c - dot(c, LUMA);
  float m = length(d);
  d *= min(m * 3.0, 0.38) / max(m, 0.0001);
  return 1.0 + d - max(d.r, max(d.g, d.b));
}
`

  const HIGHP = `
#ifdef GL_FRAGMENT_PRECISION_HIGH
precision highp float;
#else
precision mediump float;
#endif
`

  const FLAKE_VERT = SHARED + `
attribute vec3 aPos;
uniform vec2 uRes;
uniform float uScale;
uniform float uClock;
varying vec3 vSpot;
varying vec4 vSize;
varying vec3 vWeight;
varying vec4 vIri;

void main() {
  bool big = aPos.z > 0.5;
  vec2 id = aPos.xy;
  float seed = big ? 9.3 : 1.7;
  float cell = uScale * 1.06 * (big ? 8.5 : 4.8);
  vec3 h = hash32(id + seed);

  float c = uClock + fract(h.z * 7.13 + h.x * 3.71);
  float k = floor(c);
  float lo = big ? 0.78 : 0.625;
  float on = mix(smoothstep(lo, 1.0, hash12(id * 1.37 + seed + mod(k, LOOP) * 17.31)),
                 smoothstep(lo, 1.0, hash12(id * 1.37 + seed + mod(k + 1.0, LOOP) * 17.31)),
                 smoothstep(0.0, 1.0, c - k));
  gl_PointSize = 1.0;
  if (on < 0.004) {
    gl_Position = vec4(2.0, 2.0, 0.0, 1.0);
    return;
  }

  float base = big ? mix(0.5, 1.1, pow(h.z, 2.6)) : mix(0.2, 0.62, pow(h.z, 1.5));
  float sz = clamp((base - 0.2) / 0.9, 0.0, 1.0);
  float rad = base * uScale * 1.06 * (0.7 + 0.3 * on);
  vSize = vec4(rad, rad * 0.2 + 0.3, rad * mix(1.2, 5.0, sz), on);
  vWeight = vec3(0.0765 * smoothstep(0.1, 0.8, sz), 0.0315 * smoothstep(0.55, 1.0, sz), 0.3 * sz);

  vec3 h2 = hash32(id * 2.17 + seed + 5.0);
  vIri = vec4(0.65 + 0.35 * cos(6.2832 * (h2.y + uClock * 0.375 + vec3(0.0, 0.33, 0.67))), 0.33 * step(0.7, h2.x));

  vec2 center = floor((id + 0.15 + 0.7 * h.xy) * cell) + 0.5;
  float reach = cell * 0.65;
  vSpot = vec3(center, reach * reach);
  vec2 at = clamp(center, vec2(0.5), uRes - 0.5);
  gl_Position = vec4(at.x / uRes.x * 2.0 - 1.0, 1.0 - at.y / uRes.y * 2.0, 0.0, 1.0);
  gl_PointSize = 2.0 * ceil(reach) + 1.0;
}
`

  const FLAKE_FRAG = HIGHP + SHARED + `
uniform sampler2D uTex;
uniform vec2 uRes;
varying vec3 vSpot;
varying vec4 vSize;
varying vec3 vWeight;
varying vec4 vIri;

float taper(float x) {
  x = max(1.0 - x, 0.0);
  return x * x;
}

void main() {
  vec2 px = vec2(gl_FragCoord.x, uRes.y - gl_FragCoord.y);
  vec2 d = px - vSpot.xy;
  float dd = dot(d, d);
  if (dd > vSpot.z) discard;
  float rad = vSize.x;
  float th = vSize.y;
  float len = vSize.z;
  vec2 ad = abs(d);
  vec2 dg = abs(vec2(d.x + d.y, d.x - d.y)) * 0.7071;
  float rays = taper(ad.x / len) * exp(-ad.y / th) + taper(ad.y / len) * exp(-ad.x / th);
  float diag = taper(dg.x / (len * 0.45)) * exp(-dg.y / th) + taper(dg.y / (len * 0.45)) * exp(-dg.x / th);
  float core = exp(-dd / (rad * rad));
  float shape = core + rays * vWeight.x + diag * vWeight.y + exp(-dd / (rad * rad * 6.0)) * vWeight.z;
  vec3 g = texture2D(uTex, px / uRes).rgb;
  vec3 col = mix(mix(tintOf(g), vIri.rgb, vIri.a), vec3(1.0), core * 0.8);
  gl_FragColor = vec4(col * vSize.w * shape * 0.99 * (0.55 + 0.6 * dot(g, LUMA)), 1.0);
}
`

  const BASE_VERT = `
attribute vec3 aPos;
void main() {
  gl_Position = vec4(aPos.xy, 0.0, 1.0);
}
`

  const BASE_FRAG = HIGHP + SHARED + `
uniform sampler2D uTex;
uniform vec2 uRes;
uniform float uScale;
uniform float uClock;

vec3 tone(vec3 g, vec3 tint, float deep, float v) {
  return v < 0.25 ? g * (1.0 - deep)
    : v < 0.6 ? g
    : v < 0.88 ? mix(g, tint, 0.3)
    : v < 0.975 ? mix(g, tint, 0.6)
    : mix(g, vec3(1.0), 0.85);
}

void main() {
  vec2 px = vec2(gl_FragCoord.x, uRes.y - gl_FragCoord.y);
  vec3 g = texture2D(uTex, px / uRes).rgb;
  vec3 tint = tintOf(g);
  float deep = 0.2 * (1.0 - smoothstep(0.62, 0.9, dot(g, LUMA)));
  vec2 sc = floor(px / max(uScale, 1.0));
  float c = uClock + hash12(sc * 0.71 + 5.1);
  float k = floor(c);
  vec2 sk = sc * 1.13 + 3.3;
  vec3 seq = mix(tone(g, tint, deep, hash12(sk + mod(k, LOOP) * 13.17)),
                 tone(g, tint, deep, hash12(sk + mod(k + 1.0, LOOP) * 13.17)),
                 smoothstep(0.0, 1.0, c - k));
  gl_FragColor = vec4(mix(g, seq, 0.222), 1.0);
}
`

  const canvas = document.createElement('canvas')
  canvas.className = 'shimmer'
  canvas.setAttribute('aria-hidden', 'true')
  const gl = canvas.getContext('webgl', {
    alpha: false,
    antialias: false,
    depth: false,
    stencil: false,
    powerPreference: 'low-power'
  })
  if (!gl || gl.getParameter(gl.ALIASED_POINT_SIZE_RANGE)[1] < 25) return

  const compile = (type, source) => {
    const shader = gl.createShader(type)
    gl.shaderSource(shader, source)
    gl.compileShader(shader)
    return gl.getShaderParameter(shader, gl.COMPILE_STATUS) ? shader : null
  }

  const link = (vert, frag) => {
    const vs = compile(gl.VERTEX_SHADER, vert)
    const fs = compile(gl.FRAGMENT_SHADER, frag)
    if (!vs || !fs) return null
    const program = gl.createProgram()
    gl.attachShader(program, vs)
    gl.attachShader(program, fs)
    gl.bindAttribLocation(program, 0, 'aPos')
    gl.linkProgram(program)
    if (!gl.getProgramParameter(program, gl.LINK_STATUS)) return null
    return {
      program,
      uRes: gl.getUniformLocation(program, 'uRes'),
      uScale: gl.getUniformLocation(program, 'uScale'),
      uClock: gl.getUniformLocation(program, 'uClock')
    }
  }

  const flakes = link(FLAKE_VERT, FLAKE_FRAG)
  const base = link(BASE_VERT, BASE_FRAG)
  if (!flakes || !base) return

  gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer())
  gl.enableVertexAttribArray(0)
  gl.vertexAttribPointer(0, 3, gl.FLOAT, false, 0, 0)

  gl.bindTexture(gl.TEXTURE_2D, gl.createTexture())
  gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR)
  gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR)
  gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE)
  gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE)
  gl.enable(gl.BLEND)

  const scratch = document.createElement('canvas')
  const probe = document.createElement('div')
  probe.style.cssText = 'position:fixed;top:0;left:0;height:0;visibility:hidden;pointer-events:none;width:var(--bg-width, 0px)'
  const still = window.matchMedia('(prefers-reduced-motion: reduce)').matches

  let img = null
  let url = ''
  let sized = false
  let count = 0
  let clock = 0
  let frame = -1
  let last = 0
  let raf = 0

  const fit = () => {
    const rect = canvas.getBoundingClientRect()
    const area = Math.max(1, rect.width * rect.height)
    const scale = Math.min(window.devicePixelRatio || 1, 2, Math.sqrt(6e6 / area))
    const width = Math.max(2, Math.round(rect.width * scale))
    const height = Math.max(2, Math.round(rect.height * scale))

    const ratio = img.naturalHeight / img.naturalWidth
    const w = Math.max(probe.getBoundingClientRect().width, rect.width, rect.height / ratio)
    const h = w * ratio
    const [left, top] = getComputedStyle(document.body, '::before').backgroundPosition.split(' ')
    const place = (value, room) => value.endsWith('%') ? room * parseFloat(value) / 100 : parseFloat(value)
    const sx = width / rect.width
    const sy = height / rect.height

    scratch.width = width
    scratch.height = height
    const ctx = scratch.getContext('2d')
    ctx.imageSmoothingQuality = 'high'
    ctx.drawImage(img, place(left, rect.width - w) * sx, place(top, rect.height - h) * sy, w * sx, h * sy)
    gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, scratch)
    scratch.width = 1
    scratch.height = 1

    const points = [-1, -1, 0, 3, -1, 0, -1, 3, 0]
    ;[4.8, 8.5].forEach((size, layer) => {
      const cell = scale * 1.06 * size
      for (let y = -1; y <= Math.ceil(height / cell); y++) {
        for (let x = -1; x <= Math.ceil(width / cell); x++) points.push(x, y, layer)
      }
    })
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(points), gl.STATIC_DRAW)
    count = points.length / 3 - 3

    canvas.width = width
    canvas.height = height
    gl.viewport(0, 0, width, height)
    for (const pass of [flakes, base]) {
      gl.useProgram(pass.program)
      gl.uniform2f(pass.uRes, width, height)
      gl.uniform1f(pass.uScale, scale)
    }
    sized = true
  }

  const draw = () => {
    gl.clear(gl.COLOR_BUFFER_BIT)
    gl.useProgram(flakes.program)
    gl.uniform1f(flakes.uClock, clock)
    gl.blendFunc(gl.ONE, gl.ONE)
    gl.drawArrays(gl.POINTS, 3, count)
    gl.useProgram(base.program)
    gl.uniform1f(base.uClock, clock)
    gl.blendFunc(gl.ONE_MINUS_DST_COLOR, gl.ONE)
    gl.drawArrays(gl.TRIANGLES, 0, 3)
  }

  function tick (now) {
    raf = 0
    if (gl.isContextLost()) {
      canvas.remove()
      return
    }
    if (!img) return
    const f = still ? 0 : Math.floor(now / FRAME)
    if (!sized || f !== frame) {
      if (!sized) fit()
      else clock = (clock + Math.min(now - last, 250) * SPEED / 1000) % LOOP
      frame = f
      last = now
      draw()
      canvas.classList.add('is-ready')
    }
    if (!still) raf = requestAnimationFrame(tick)
  }

  const wake = () => {
    if (!raf) raf = requestAnimationFrame(tick)
  }

  const backgroundUrl = () => {
    const match = /url\(["']?(.*?)["']?\)/.exec(getComputedStyle(document.body, '::before').backgroundImage)
    return match ? match[1] : ''
  }

  const refit = () => {
    sized = false
    const next = backgroundUrl()
    if (next !== url) {
      url = next
      img = null
      canvas.classList.remove('is-ready')
      const image = new Image()
      image.onload = () => {
        if (url !== next) return
        img = image
        wake()
      }
      if (next) image.src = next
    }
    wake()
  }

  document.body.prepend(canvas)
  document.body.append(probe)
  window.addEventListener('resize', refit)
  document.addEventListener('DOMContentLoaded', refit)
  refit()
})()
