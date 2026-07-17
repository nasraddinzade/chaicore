import type { Content } from "./types";

/** Origin PHP-бэкенда (headless CMS). Задаётся в BACKEND_URL (Vercel env). */
export const BACKEND =
  (process.env.BACKEND_URL || "https://chaicore.az").replace(/\/$/, "");

/** Пустой контент — фолбэк, если API недоступен (сайт не падает целиком). */
const EMPTY: Content = {
  texts: {},
  settings: {},
  images: {},
  team: [],
  reviews: { az: [], ru: [], en: [] },
  sections: [],
};

/** Весь контент сайта из headless-API. ISR: перезапрос раз в 60 сек. */
export async function getContent(): Promise<Content> {
  try {
    const res = await fetch(`${BACKEND}/api.php`, {
      next: { revalidate: 60 },
    });
    if (!res.ok) throw new Error(`Content API responded ${res.status}`);
    return (await res.json()) as Content;
  } catch (err) {
    console.error("getContent failed:", err);
    return EMPTY;
  }
}
