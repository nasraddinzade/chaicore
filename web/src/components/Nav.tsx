"use client";

import { useEffect } from "react";
import type { Lang } from "@/lib/types";

type NavLabels = {
  about: string;
  philosophy: string;
  services: string;
  ceremony: string;
  team: string;
  gallery: string;
  testimonials: string;
  contact: string;
};

export default function Nav({
  lang,
  logoImg,
  logoSub,
  labels,
}: {
  lang: Lang;
  logoImg: string;
  logoSub: string;
  labels: NavLabels;
}) {
  useEffect(() => {
    const onScroll = () => {
      const nav = document.getElementById("navbar");
      if (nav) nav.classList.toggle("scrolled", window.scrollY > 60);
    };
    window.addEventListener("scroll", onScroll);
    onScroll();
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const setLang = (l: Lang) => {
    document.cookie = `lang=${l};path=/;max-age=${60 * 60 * 24 * 365}`;
    window.location.reload();
  };

  const toggleNav = () => document.body.classList.toggle("nav-mobile-open");
  const closeNav = () => document.body.classList.remove("nav-mobile-open");

  return (
    <nav id="navbar">
      <div className="nav-inner">
        <a href="#hero" className="nav-logo" onClick={closeNav}>
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src={logoImg} alt="ChaiCore" className="nav-logo-img" />
          <div>
            <span className="nav-logo-sub active">{logoSub}</span>
          </div>
        </a>

        <ul className="nav-links">
          <li data-sec="about">
            <a href="#about" onClick={closeNav}>
              {labels.about}
            </a>
          </li>
          <li data-sec="philosophy">
            <a href="#philosophy" onClick={closeNav}>
              {labels.philosophy}
            </a>
          </li>
          <li data-sec="services">
            <a href="#services" onClick={closeNav}>
              {labels.services}
            </a>
          </li>
          <li className="nav-extra" data-sec="ceremony">
            <a href="#ceremony" onClick={closeNav}>
              {labels.ceremony}
            </a>
          </li>
          <li className="nav-extra" data-sec="team">
            <a href="#team" onClick={closeNav}>
              {labels.team}
            </a>
          </li>
          <li data-sec="gallery">
            <a href="#gallery" onClick={closeNav}>
              {labels.gallery}
            </a>
          </li>
          <li className="nav-extra" data-sec="testimonials">
            <a href="#testimonials" onClick={closeNav}>
              {labels.testimonials}
            </a>
          </li>
          <li data-sec="contact">
            <a href="#contact" onClick={closeNav}>
              {labels.contact}
            </a>
          </li>
        </ul>

        <div className="nav-right">
          <div className="lang-switch">
            {(["az", "ru", "en"] as Lang[]).map((l) => (
              <button
                key={l}
                className={`lang-btn ${l === lang ? "active" : ""}`}
                onClick={() => setLang(l)}
              >
                {l.toUpperCase()}
              </button>
            ))}
          </div>
          <div className="nav-toggle" onClick={toggleNav}>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
    </nav>
  );
}
