"use client";

import { useState } from "react";

export type ContactLabels = {
  formTitle: string;
  name: string;
  surname: string;
  phone: string;
  service: string;
  date: string;
  guests: string;
  message: string;
  send: string;
  services: string[];
  thanks: string;
  fail: string;
};

export default function ContactForm({ labels }: { labels: ContactLabels }) {
  const [status, setStatus] = useState<"idle" | "sending" | "ok" | "err">(
    "idle"
  );

  async function onSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const form = e.currentTarget;
    setStatus("sending");
    const data = new FormData(form);
    try {
      const res = await fetch("/api/contact", { method: "POST", body: data });
      const json = (await res.json()) as { ok: boolean };
      setStatus(json.ok ? "ok" : "err");
      if (json.ok) form.reset();
    } catch {
      setStatus("err");
    }
  }

  return (
    <form className="contact-form" onSubmit={onSubmit}>
      <h3>{labels.formTitle}</h3>
      <div className="form-row">
        <div className="form-group">
          <label>{labels.name}</label>
          <input type="text" name="name" placeholder="— — —" required />
        </div>
        <div className="form-group">
          <label>{labels.surname}</label>
          <input type="text" name="surname" placeholder="— — —" />
        </div>
      </div>
      <div className="form-row">
        <div className="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="— — —" />
        </div>
        <div className="form-group">
          <label>{labels.phone}</label>
          <input type="tel" name="phone" placeholder="— — —" required />
        </div>
      </div>
      <div className="form-group">
        <label>{labels.service}</label>
        <select name="service">
          {labels.services.map((s, i) => (
            <option key={i}>{s}</option>
          ))}
        </select>
      </div>
      <div className="form-row">
        <div className="form-group">
          <label>{labels.date}</label>
          <input type="date" name="date" />
        </div>
        <div className="form-group">
          <label>{labels.guests}</label>
          <input type="number" name="guests" min="1" placeholder="1" />
        </div>
      </div>
      <div className="form-group">
        <label>{labels.message}</label>
        <textarea name="message" placeholder="— — —"></textarea>
      </div>
      <button
        type="submit"
        className="btn btn-crimson"
        style={{ width: "100%", justifyContent: "center" }}
        disabled={status === "sending"}
      >
        <span>{labels.send}</span>
        <i className="fa-solid fa-paper-plane"></i>
      </button>
      {status === "ok" && (
        <p style={{ marginTop: 14, color: "#8fe0b4" }}>{labels.thanks}</p>
      )}
      {status === "err" && (
        <p style={{ marginTop: 14, color: "#f0a0a0" }}>{labels.fail}</p>
      )}
    </form>
  );
}
