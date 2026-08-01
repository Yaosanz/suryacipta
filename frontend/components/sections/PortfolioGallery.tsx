"use client";

import { useMemo, useState } from "react";
import { X } from "lucide-react";
import PortfolioCard from "@/components/common/PortfolioCard";
import type { PortfolioItem } from "@/lib/cms-types";

/** Galeri portofolio terfilter dengan dialog detail native. */
export default function PortfolioGallery({ projects }: { projects: PortfolioItem[] }) {
  const categories = ["Semua", ...Array.from(new Set(projects.map((item) => item.category)))];
  const [active, setActive] = useState("Semua");
  const [selected, setSelected] = useState<PortfolioItem | null>(null);
  const items = useMemo(
    () => (active === "Semua" ? projects : projects.filter((item) => item.category === active)),
    [active, projects],
  );

  return (
    <>
      <div className="flex gap-2 overflow-x-auto pb-2" role="tablist" aria-label="Filter proyek">
        {categories.map((category) => (
          <button
            key={category}
            role="tab"
            aria-selected={active === category}
            onClick={() => setActive(category)}
            className={`whitespace-nowrap rounded-sm border px-4 py-2 text-sm font-bold ${
              active === category ? "border-navy bg-navy text-white" : "border-line bg-white text-muted"
            }`}
          >
            {category}
          </button>
        ))}
      </div>
      <div className="mt-7 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        {items.map((item) => (
          <PortfolioCard key={item.id} item={item} onOpen={() => setSelected(item)} />
        ))}
      </div>
      {selected && (
        <div
          className="fixed inset-0 z-[60] grid place-items-center bg-black/70 p-4"
          role="dialog"
          aria-modal="true"
          aria-labelledby="project-dialog-title"
          onClick={() => setSelected(null)}
        >
          <div
            className="relative max-h-[calc(100svh-2rem)] w-full max-w-2xl overflow-y-auto rounded-md bg-white p-7"
            onClick={(event) => event.stopPropagation()}
          >
            <button
              type="button"
              onClick={() => setSelected(null)}
              className="absolute right-3 top-3 grid size-10 place-items-center"
              aria-label="Tutup detail proyek"
            >
              <X aria-hidden="true" />
            </button>
            <p className="eyebrow">{selected.category} / {selected.year}</p>
            <h2 id="project-dialog-title" className="mt-3 pr-8 font-display text-3xl font-bold text-navy">
              {selected.title}
            </h2>
            <p className="mt-2 text-sm font-semibold text-muted">{selected.location}</p>
            {selected.clientName && (
              <p className="mt-2 text-sm text-muted">
                Klien: <span className="font-semibold text-navy">{selected.clientName}</span>
              </p>
            )}
            {selected.video && (
              <div className="mt-5 aspect-video overflow-hidden rounded-md bg-navy-dark">
                <video
                  className="size-full object-contain"
                  poster={selected.image ?? undefined}
                  controls
                  playsInline
                  preload="metadata"
                  aria-label={selected.video.alt ?? `Video proyek ${selected.title}`}
                >
                  <source src={selected.video.url} type="video/mp4" />
                </video>
              </div>
            )}
            <p className="mt-5 leading-7 text-muted">{selected.description}</p>
          </div>
        </div>
      )}
    </>
  );
}
