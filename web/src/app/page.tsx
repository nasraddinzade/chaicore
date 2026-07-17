import { cookies } from "next/headers";
import { getContent } from "@/lib/api";
import { makeT, normalizeLang, triText, firstImage, allImages } from "@/lib/i18n";
import type { Lang } from "@/lib/types";
import Nav from "@/components/Nav";
import ReviewForm from "@/components/ReviewForm";
import ContactForm from "@/components/ContactForm";
import NewsletterForm from "@/components/NewsletterForm";
import Effects from "@/components/Effects";

const PHIL_ICONS = [
  "fa-scroll",
  "fa-spa",
  "fa-handshake",
  "fa-seedling",
  "fa-book-open",
  "fa-globe-asia",
];
const SVC_ICONS = [
  "fa-fire-flame-curved",
  "fa-wine-glass",
  "fa-chalkboard-user",
  "fa-car-side",
  "fa-users",
  "fa-store",
];

export default async function Home() {
  const content = await getContent();
  const cookieStore = await cookies();
  const lang: Lang = normalizeLang(
    cookieStore.get("lang")?.value,
    normalizeLang(content.settings.default_lang, "az")
  );
  const t = makeT(content, lang);
  const s = (k: string, d = "") => content.settings[k] ?? d;
  const img = (slot: string) => firstImage(content, slot) ?? "";
  const reviews = content.reviews[lang] ?? [];
  const soc = (k: string) => s(k, "#") || "#";

  return (
    <>
      <Nav
        lang={lang}
        logoImg={img("logo_nav")}
        logoSub={t("logo_sub")}
        labels={{
          about: t("nav_about"),
          philosophy: t("nav_philosophy"),
          services: t("nav_services"),
          ceremony: t("nav_ceremony"),
          team: t("nav_team"),
          gallery: t("nav_gallery"),
          testimonials: t("nav_testimonials"),
          contact: t("nav_contact"),
        }}
      />

      {/* HERO */}
      <section id="hero">
        <div className="hero-bg-img">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src={img("hero_bg")} alt="background" />
        </div>
        <div className="hero-overlay"></div>
        <div className="particles" id="particles"></div>
        <div className="hero-content">
          <div className="hero-logo-wrap">
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img src={img("hero_logo")} alt="ChaiCore Logo" />
          </div>
          <div>
            <p className="hero-eyebrow">{t("hero_eyebrow")}</p>
            <h1 className="hero-title">{t("hero_title")}</h1>
            <p className="hero-subtitle">{t("hero_subtitle")}</p>
            <div className="ornament">
              <i className="fa-solid fa-leaf"></i>
            </div>
            <p className="hero-desc">{t("hero_desc")}</p>
            <div className="hero-actions">
              <a href="#ceremony" data-sec="ceremony" className="btn btn-crimson">
                <i className="fa-solid fa-mug-hot"></i> {t("hero_btn1")}
              </a>
              <a href="#contact" data-sec="contact" className="btn btn-outline">
                <i className="fa-solid fa-calendar"></i> {t("hero_btn2")}
              </a>
            </div>
          </div>
        </div>
        <div className="hero-scroll">
          <span>{t("hero_scroll")}</span>
          <i className="fa-solid fa-chevron-down"></i>
        </div>
      </section>

      {/* CRIMSON BAND */}
      <div className="crimson-band">
        <div className="band-inner">
          <p className="band-text">{t("band_text")}</p>
          <a href="#contact" className="band-cta">
            {t("band_cta")}
          </a>
        </div>
      </div>

      {/* ABOUT */}
      <section id="about">
        <div className="container">
          <div className="about-grid">
            <div className="about-imgs">
              <div className="about-img-main">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img src={img("about_main")} alt={s("brand_name")} />
              </div>
              <div className="about-img-accent">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img src={img("about_accent")} alt={s("brand_name")} />
              </div>
              {img("about_badge") && (
                <div className="about-badge">
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img src={img("about_badge")} alt="Dragon" />
                </div>
              )}
            </div>
            <div className="about-text">
              <span className="section-tag">{t("about_tag")}</span>
              <h2 className="section-title">{t("about_title")}</h2>
              <div className="ornament">
                <i className="fa-solid fa-leaf"></i>
              </div>
              <p>{t("about_p1")}</p>
              <p>{t("about_p2")}</p>
              <p>{t("about_p3")}</p>
              <a href="#services" className="btn btn-orange">
                <i className="fa-solid fa-arrow-right"></i> {t("about_btn")}
              </a>
              <div className="about-stats">
                {[1, 2, 3, 4].map((n) => (
                  <div className="stat-card" key={n}>
                    <div className="stat-num">{s(`stat${n}_num`)}</div>
                    <div className="stat-label">{t(`stat${n}_label`)}</div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* PHILOSOPHY */}
      <section id="philosophy">
        <div className="container">
          <div className="section-header centered">
            <span className="section-tag">{t("phil_tag")}</span>
            <h2 className="section-title">{t("phil_title")}</h2>
            <div className="ornament">
              <i className="fa-solid fa-yin-yang"></i>
            </div>
            <p className="section-lead">{t("phil_lead")}</p>
          </div>
          <div className="phil-grid">
            {[1, 2, 3, 4, 5, 6].map((n) => (
              <div className="phil-card" key={n}>
                <div className="phil-icon">
                  <i className={`fa-solid ${PHIL_ICONS[n - 1]}`}></i>
                </div>
                <h3>{t(`phil${n}_title`)}</h3>
                <p>{t(`phil${n}_text`)}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* SERVICES */}
      <section id="services">
        <div className="container">
          <div className="section-header">
            <span className="section-tag">{t("svc_tag")}</span>
            <h2 className="section-title">{t("svc_title")}</h2>
            <div className="ornament">
              <i className="fa-solid fa-mug-hot"></i>
            </div>
            <p className="section-lead">{t("svc_lead")}</p>
          </div>
          <div className="services-grid">
            {[1, 2, 3, 4, 5, 6].map((n) => (
              <div className="svc-card" key={n}>
                <div className="svc-icon">
                  <i className={`fa-solid ${SVC_ICONS[n - 1]}`}></i>
                </div>
                <div className="svc-body">
                  <h3>{t(`svc${n}_title`)}</h3>
                  <p>{t(`svc${n}_text`)}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CEREMONY */}
      <section id="ceremony">
        <div className="container">
          <div className="ceremony-inner">
            <div>
              <div className="ceremony-img">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img src={img("ceremony_img")} alt="Ceremony" />
                <div className="ceremony-img-badge">
                  <i className="fa-solid fa-star"></i>
                  <span>{t("cer_badge")}</span>
                </div>
              </div>
            </div>
            <div>
              <div>
                <span className="section-tag">{t("cer_tag")}</span>
                <h2 className="section-title">{t("cer_title")}</h2>
                <div className="ornament">
                  <i className="fa-solid fa-mug-hot"></i>
                </div>
                <p className="section-lead">{t("cer_lead")}</p>
              </div>
              <div className="ceremony-steps">
                {[1, 2, 3, 4].map((n) => (
                  <div className="step" key={n}>
                    <div className="step-num">{`0${n}`}</div>
                    <div className="step-body">
                      <h4>{t(`step${n}_title`)}</h4>
                      <p>{t(`step${n}_text`)}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* TEAM */}
      <section id="team">
        <div className="container">
          <div className="section-header centered">
            <span className="section-tag">{t("team_tag")}</span>
            <h2 className="section-title">{t("team_title")}</h2>
            <div className="ornament">
              <i className="fa-solid fa-user-tie"></i>
            </div>
          </div>
          <div className="team-grid">
            {content.team.map((m) => (
              <div className="team-card" key={m.id}>
                {m.photo && (
                  <div className="team-photo">
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    <img src={m.photo} alt="Tea Master" />
                  </div>
                )}
                <div className="team-info">
                  {triText(m.name, lang).trim() !== "" && (
                    <h3 className="team-name">{triText(m.name, lang)}</h3>
                  )}
                  <div className="team-role">{triText(m.role, lang)}</div>
                  <div className="team-divider"></div>
                  <p className="team-desc">{triText(m.desc, lang)}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* GALLERY */}
      <section id="gallery">
        <div className="container">
          <div className="section-header centered">
            <span className="section-tag">{t("gal_tag")}</span>
            <h2 className="section-title">{t("gal_title")}</h2>
            <div className="ornament">
              <i className="fa-solid fa-images"></i>
            </div>
          </div>
          <div className="gallery-grid">
            {allImages(content, "gallery").map((g, idx) => (
              <div
                className={idx === 0 ? "g-item wide" : "g-item"}
                style={idx === 0 ? { gridRow: "span 2" } : undefined}
                key={idx}
              >
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img src={g} alt="Gallery" />
                <div className="g-overlay">
                  <i className="fa-solid fa-magnifying-glass-plus"></i>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* TESTIMONIALS */}
      <section id="testimonials">
        <div className="container">
          <div className="section-header centered">
            <span className="section-tag">{t("testi_tag")}</span>
            <h2 className="section-title">{t("testi_title")}</h2>
            <div className="ornament">
              <i className="fa-solid fa-star"></i>
            </div>
            <p className="section-lead">{t("testi_lead")}</p>
          </div>

          {reviews.length > 0 && (
            <div className="testi-grid">
              {reviews.map((r, idx) => {
                const rating = Math.max(1, Math.min(5, r.rating));
                return (
                  <div className="testi-card" key={idx}>
                    <div className="testi-stars">
                      {[...Array(rating).keys()].map((i) => (
                        <i className="fa-solid fa-star" key={i}></i>
                      ))}
                    </div>
                    <p className="testi-text" style={{ whiteSpace: "pre-line" }}>
                      {r.text}
                    </p>
                    <div className="testi-author">
                      <div className="testi-av">
                        <i className="fa-solid fa-user"></i>
                      </div>
                      <div>
                        {r.name.trim() !== "" && (
                          <div className="testi-name">{r.name}</div>
                        )}
                        {r.location.trim() !== "" && (
                          <div className="testi-role">{r.location}</div>
                        )}
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}

          <ReviewForm
            lang={lang}
            labels={{
              cta: t("review_cta"),
              formTitle: t("review_form_title"),
              name: t("review_f_name"),
              place: t("review_f_place"),
              text: t("review_f_text"),
              rating: t("review_f_rating"),
              submit: t("review_submit"),
              thanks: t("review_thanks"),
            }}
          />
        </div>
      </section>

      {/* CUSTOM SECTIONS */}
      {content.sections.map((cs) => {
        const tag = triText(cs.tag, lang);
        const title = triText(cs.title, lang);
        const body = triText(cs.body, lang);
        if (title.trim() === "" && body.trim() === "" && !cs.image) return null;
        return (
          <section
            id={`sec-${cs.id}`}
            className="custom-section"
            style={{ background: `var(${cs.bg ? "--navy-2" : "--navy"})` }}
            key={cs.id}
          >
            <div className="container">
              {(tag.trim() !== "" || title.trim() !== "") && (
                <div className="section-header centered">
                  {tag.trim() !== "" && (
                    <span className="section-tag">{tag}</span>
                  )}
                  {title.trim() !== "" && (
                    <h2 className="section-title">{title}</h2>
                  )}
                  <div className="ornament">
                    <i className="fa-solid fa-leaf"></i>
                  </div>
                </div>
              )}
              {cs.image && (
                <div className="cs-image">
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img src={cs.image} alt="" />
                </div>
              )}
              {body.trim() !== "" && (
                <div className="cs-body" style={{ whiteSpace: "pre-line" }}>
                  {body}
                </div>
              )}
            </div>
          </section>
        );
      })}

      {/* CONTACT */}
      <section id="contact">
        <div className="container">
          <div className="section-header">
            <span className="section-tag">{t("contact_tag")}</span>
            <h2 className="section-title">{t("contact_title")}</h2>
            <div className="ornament">
              <i className="fa-solid fa-envelope"></i>
            </div>
          </div>
          <div className="contact-grid">
            <div>
              <div className="contact-items">
                <div className="contact-item">
                  <div className="ci-icon">
                    <i className="fa-solid fa-location-dot"></i>
                  </div>
                  <div>
                    <div className="ci-label">{t("ci_address_label")}</div>
                    <div className="ci-val">{t("ci_address_val")}</div>
                  </div>
                </div>
                <div className="contact-item">
                  <div className="ci-icon">
                    <i className="fa-solid fa-phone"></i>
                  </div>
                  <div>
                    <div className="ci-label">{t("ci_phone_label")}</div>
                    <div className="ci-val">{s("contact_phone")}</div>
                  </div>
                </div>
                <div className="contact-item">
                  <div className="ci-icon">
                    <i className="fa-solid fa-envelope"></i>
                  </div>
                  <div>
                    <div className="ci-label">Email</div>
                    <div className="ci-val">{s("contact_email")}</div>
                  </div>
                </div>
                <div className="contact-item">
                  <div className="ci-icon">
                    <i className="fa-brands fa-instagram"></i>
                  </div>
                  <div>
                    <div className="ci-label">Instagram</div>
                    <div className="ci-val">{s("contact_instagram")}</div>
                  </div>
                </div>
              </div>
              <div className="social-row">
                <a href={soc("soc_instagram")} className="soc-btn" target="_blank" rel="noopener">
                  <i className="fa-brands fa-instagram"></i>
                </a>
                <a href={soc("soc_facebook")} className="soc-btn" target="_blank" rel="noopener">
                  <i className="fa-brands fa-facebook-f"></i>
                </a>
                <a href={soc("soc_whatsapp")} className="soc-btn" target="_blank" rel="noopener">
                  <i className="fa-brands fa-whatsapp"></i>
                </a>
                <a href={soc("soc_telegram")} className="soc-btn" target="_blank" rel="noopener">
                  <i className="fa-brands fa-telegram"></i>
                </a>
                <a href={soc("soc_youtube")} className="soc-btn" target="_blank" rel="noopener">
                  <i className="fa-brands fa-youtube"></i>
                </a>
                <a href={soc("soc_tiktok")} className="soc-btn" target="_blank" rel="noopener">
                  <i className="fa-brands fa-tiktok"></i>
                </a>
              </div>
              {s("map_embed").trim() !== "" ? (
                <div
                  className="map-ph"
                  style={{ padding: 0, overflow: "hidden", borderStyle: "solid" }}
                  dangerouslySetInnerHTML={{ __html: s("map_embed") }}
                />
              ) : (
                <div className="map-ph">
                  <i className="fa-solid fa-map-location-dot"></i>
                  <span>{t("map_label")}</span>
                </div>
              )}
            </div>

            <div>
              <ContactForm
                labels={{
                  formTitle: t("form_title"),
                  name: t("f_name"),
                  surname: t("f_surname"),
                  phone: t("f_phone"),
                  service: t("f_service"),
                  date: t("f_date"),
                  guests: t("f_guests"),
                  message: t("f_message"),
                  send: t("f_send"),
                  services: [
                    t("opt_ceremony"),
                    t("opt_tasting"),
                    t("opt_masterclass"),
                    t("opt_corporate"),
                  ],
                  thanks:
                    lang === "ru"
                      ? "Спасибо! Заявка отправлена."
                      : lang === "en"
                        ? "Thank you! Your request was sent."
                        : "Təşəkkürlər! Müraciətiniz göndərildi.",
                  fail:
                    lang === "ru"
                      ? "Не удалось отправить. Попробуйте позже."
                      : lang === "en"
                        ? "Could not send. Please try later."
                        : "Göndərilmədi. Sonra yenidən cəhd edin.",
                }}
              />
            </div>
          </div>
        </div>
      </section>

      {/* FOOTER */}
      <footer>
        <div className="container">
          <div className="footer-grid">
            <div className="footer-brand">
              <div className="footer-logo">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img src={img("logo_footer")} alt={s("brand_name")} />
                <span className="nav-logo-text">{s("brand_name")}</span>
              </div>
              <p>{t("footer_about")}</p>
            </div>
            <div className="footer-col">
              <h4>{t("footer_nav_title")}</h4>
              <ul>
                <li><a href="#about">{t("nav_about")}</a></li>
                <li><a href="#philosophy">{t("nav_philosophy")}</a></li>
                <li><a href="#services">{t("nav_services")}</a></li>
                <li><a href="#gallery">{t("nav_gallery")}</a></li>
                <li><a href="#contact">{t("nav_contact")}</a></li>
              </ul>
            </div>
            <div className="footer-col">
              <h4>{t("footer_svc_title")}</h4>
              <ul>
                <li><a href="#services">{t("footer_svc1")}</a></li>
                <li><a href="#services">{t("footer_svc2")}</a></li>
                <li><a href="#services">{t("footer_svc3")}</a></li>
                <li><a href="#services">{t("footer_svc4")}</a></li>
              </ul>
            </div>
            <div className="footer-col footer-newsletter">
              <h4>{t("footer_news_title")}</h4>
              <p>{t("footer_news_text")}</p>
              <NewsletterForm />
            </div>
          </div>
          <div className="footer-bottom">
            <span className="footer-copy">
              © {s("copyright_year")} {s("brand_name")}. {t("footer_copy")}
            </span>
            <div className="social-row" style={{ margin: 0 }}>
              <a href={soc("soc_instagram")} className="soc-btn" style={{ width: 34, height: 34 }} target="_blank" rel="noopener">
                <i className="fa-brands fa-instagram"></i>
              </a>
              <a href={soc("soc_facebook")} className="soc-btn" style={{ width: 34, height: 34 }} target="_blank" rel="noopener">
                <i className="fa-brands fa-facebook-f"></i>
              </a>
              <a href={soc("soc_telegram")} className="soc-btn" style={{ width: 34, height: 34 }} target="_blank" rel="noopener">
                <i className="fa-brands fa-telegram"></i>
              </a>
            </div>
          </div>
        </div>
      </footer>

      <Effects />
    </>
  );
}
