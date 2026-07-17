"use client";

import { useState } from "react";

export default function NewsletterForm() {
  const [done, setDone] = useState(false);

  async function onSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const form = e.currentTarget;
    const data = new FormData(form);
    data.set("newsletter", "1");
    try {
      await fetch("/api/contact", { method: "POST", body: data });
    } catch {
      /* ignore */
    }
    setDone(true);
    form.reset();
  }

  return (
    <form className="footer-form" onSubmit={onSubmit}>
      <input type="email" name="email" placeholder="Email" required aria-label="Email" />
      <button type="submit" aria-label="Subscribe">
        <i className={done ? "fa-solid fa-check" : "fa-solid fa-paper-plane"}></i>
      </button>
    </form>
  );
}
