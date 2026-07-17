"use client";

import { useEffect } from "react";

export default function Effects() {
  useEffect(() => {
    const pc = document.getElementById("particles");
    if (!pc || pc.dataset.done) return;
    pc.dataset.done = "1";
    for (let i = 0; i < 24; i++) {
      const p = document.createElement("div");
      p.className = "particle";
      p.style.cssText = `left:${Math.random() * 100}%;animation-duration:${
        8 + Math.random() * 12
      }s;animation-delay:${Math.random() * 10}s;width:${
        1 + Math.random() * 3
      }px;height:${1 + Math.random() * 3}px;opacity:${Math.random() * 0.5}`;
      pc.appendChild(p);
    }
  }, []);

  return null;
}
