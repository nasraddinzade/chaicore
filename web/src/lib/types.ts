export type Lang = "az" | "ru" | "en";
export const LANGS: Lang[] = ["az", "ru", "en"];

export type Tri = { az: string; ru: string; en: string };

export interface TeamMember {
  id: number;
  photo: string | null;
  name: Tri;
  role: Tri;
  desc: Tri;
}

export interface Review {
  name: string;
  location: string;
  text: string;
  rating: number;
}

export interface CustomSection {
  id: number;
  bg: number;
  tag: Tri;
  title: Tri;
  body: Tri;
  image: string | null;
}

export interface Content {
  texts: Record<string, Tri>;
  settings: Record<string, string>;
  images: Record<string, string[]>;
  team: TeamMember[];
  reviews: Record<Lang, Review[]>;
  sections: CustomSection[];
}
