import { NextResponse } from "next/server";
import { BACKEND } from "@/lib/api";

export async function POST(req: Request) {
  const form = await req.formData();
  const body = new URLSearchParams();
  for (const [k, v] of form.entries()) {
    body.append(k, typeof v === "string" ? v : "");
  }
  try {
    const res = await fetch(`${BACKEND}/review.php`, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: body.toString(),
      redirect: "follow",
      cache: "no-store",
    });
    return NextResponse.json({ ok: res.url.includes("review=1") });
  } catch {
    return NextResponse.json({ ok: false }, { status: 502 });
  }
}
