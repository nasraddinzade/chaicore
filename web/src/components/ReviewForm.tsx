"use client";

import { useState } from "react";
import type { Lang } from "@/lib/types";

export type ReviewLabels = {
  cta: string;
  formTitle: string;
  name: string;
  place: string;
  text: string;
  rating: string;
  submit: string;
  thanks: string;
};

export default function ReviewForm({
  lang,
  labels,
}: {
  lang: Lang;
  labels: ReviewLabels;
}) {
  const [open, setOpen] = useState(false);
  const [status, setStatus] = useState<"idle" | "sending" | "ok" | "err">(
    "idle"
  );

  async function onSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const form = e.currentTarget;
    setStatus("sending");
    const data = new FormData(form);
    data.set("lang", lang);
    try {
      const res = await fetch("/api/review", { method: "POST", body: data });
      const json = (await res.json()) as { ok: boolean };
      setStatus(json.ok ? "ok" : "err");
      if (json.ok) form.reset();
    } catch {
      setStatus("err");
    }
  }

  return (
    <>
      <div className="review-cta-wrap">
        <button
          type="button"
          className="btn btn-outline"
          onClick={() => setOpen((v) => !v)}
        >
          <i className="fa-solid fa-pen"></i> {labels.cta}
        </button>
      </div>

      <form
        className={`review-form ${open ? "open" : ""}`}
        onSubmit={onSubmit}
      >
        <h3>{labels.formTitle}</h3>
        <div className="form-row">
          <div className="form-group">
            <label>{labels.name}</label>
            <input
              type="text"
              name="name"
              maxLength={120}
              placeholder="— — —"
              required
            />
          </div>
          <div className="form-group">
            <label>{labels.place}</label>
            <input
              type="text"
              name="location"
              maxLength={160}
              placeholder="— — —"
            />
          </div>
        </div>
        <div className="form-group">
          <label>{labels.rating}</label>
          <select name="rating" defaultValue="5">
            <option value="5">★★★★★</option>
            <option value="4">★★★★</option>
            <option value="3">★★★</option>
            <option value="2">★★</option>
            <option value="1">★</option>
          </select>
        </div>
        <div className="form-group">
          <label>{labels.text}</label>
          <textarea
            name="text"
            maxLength={1500}
            placeholder="— — —"
            required
          ></textarea>
        </div>
        <button
          type="submit"
          className="btn btn-crimson"
          style={{ justifyContent: "center" }}
          disabled={status === "sending"}
        >
          <span>{labels.submit}</span>
          <i className="fa-solid fa-paper-plane"></i>
        </button>
        {status === "ok" && (
          <p style={{ marginTop: 14, color: "#8fe0b4" }}>{labels.thanks}</p>
        )}
      </form>
    </>
  );
}
