/**
 * ÇAYCORE — сайт (Next.js клиент).
 * © 2026 ÇAYCORE. Лицензия MIT — см. LICENSE в корне репозитория.
 */
import "./globals.css";
import type { Metadata } from "next";
import { cookies } from "next/headers";
import { getContent } from "@/lib/api";
import { dynamicCss, normalizeLang } from "@/lib/i18n";

export async function generateMetadata(): Promise<Metadata> {
  try {
    const content = await getContent();
    return {
      title: content.settings.site_title || "ChaiCore",
      description:
        content.texts.hero_desc?.az?.slice(0, 160) ||
        "ChaiCore — Azərbaycan Çay Mədəniyyəti",
    };
  } catch {
    return { title: "ChaiCore" };
  }
}

export default async function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const content = await getContent();
  const cookieStore = await cookies();
  const lang = normalizeLang(
    cookieStore.get("lang")?.value,
    normalizeLang(content.settings.default_lang, "az")
  );
  const css = dynamicCss(content.settings);

  return (
    <html lang={lang}>
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link
          href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Raleway:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500&display=swap"
          rel="stylesheet"
        />
        <link
          rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        />
        <style dangerouslySetInnerHTML={{ __html: css }} />
      </head>
      <body>{children}</body>
    </html>
  );
}
