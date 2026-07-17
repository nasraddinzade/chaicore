import type { Content, Lang, Tri } from "./types";

export const LANGS: Lang[] = ["az", "ru", "en"];

export function normalizeLang(
  v: string | undefined | null,
  fallback: Lang = "az"
): Lang {
  return v === "az" || v === "ru" || v === "en" ? v : fallback;
}

/** t(key) → строка на текущем языке (откат на az → ''). */
export function makeT(content: Content, lang: Lang) {
  return (key: string): string => {
    const t = content.texts[key];
    if (!t) return "";
    return t[lang] || t.az || "";
  };
}

export function triText(t: Tri | undefined, lang: Lang): string {
  if (!t) return "";
  return t[lang] || t.az || "";
}

export function firstImage(content: Content, slot: string): string | undefined {
  return content.images[slot]?.[0];
}

export function allImages(content: Content, slot: string): string[] {
  return content.images[slot] ?? [];
}

/** Динамический CSS из настроек: размеры шрифтов, цвета палитры,
 *  скрытие разделов на телефонах — 1:1 с логикой PHP-фронтенда. */
export function dynamicCss(settings: Record<string, string>): string {
  const scale = (k: string, def: number) => {
    const v = parseInt(settings[k] ?? "", 10);
    return (Number.isNaN(v) ? def : Math.max(50, Math.min(200, v))) / 100;
  };
  const fs = scale("font_scale", 100);
  const fsh = scale("scale_hero", 100);
  const fss = scale("scale_section", 100);
  const fsm = scale("scale_mobile", 100);

  const colorMap: Record<string, string> = {
    col_navy: "--navy",
    col_navy2: "--navy-2",
    col_navy3: "--navy-3",
    col_crimson: "--crimson",
    col_crimson2: "--crimson-2",
    col_orange: "--orange",
    col_orange2: "--orange-2",
    col_orangedim: "--orange-dim",
    col_cream: "--cream",
    col_cream2: "--cream-2",
    col_text: "--text",
    col_textdim: "--text-dim",
  };
  let colors = "";
  for (const [sk, cssVar] of Object.entries(colorMap)) {
    const v = settings[sk];
    if (v && /^#[0-9a-fA-F]{6}$/.test(v)) colors += `${cssVar}:${v};`;
  }

  const mobileSections = [
    "about",
    "philosophy",
    "services",
    "ceremony",
    "team",
    "gallery",
    "testimonials",
    "contact",
  ];
  const showDefault: Record<string, string> = {
    about: "1",
    philosophy: "0",
    services: "1",
    ceremony: "0",
    team: "0",
    gallery: "0",
    testimonials: "0",
    contact: "1",
  };
  let hide = "";
  for (const sec of mobileSections) {
    const val = settings[`mobile_show_${sec}`] ?? showDefault[sec] ?? "1";
    if (val !== "1")
      hide += `#${sec},[data-sec="${sec}"]{display:none !important;}`;
  }

  return `:root{--font-scale:${fs};--scale-hero:${fsh};--scale-section:${fss};--scale-mobile:${fsm};${colors}}
html{font-size:calc(100% * var(--font-scale));}
.hero-title{font-size:calc(clamp(3.2rem,9vw,6.5rem) * var(--scale-hero));}
.section-title{font-size:calc(clamp(1.8rem,4vw,2.8rem) * var(--scale-section));}
@media (max-width:580px){html{font-size:calc(100% * var(--font-scale) * var(--scale-mobile));}${hide}}`;
}
