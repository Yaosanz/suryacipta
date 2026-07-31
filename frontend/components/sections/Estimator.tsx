"use client";

import { useMemo, useState } from "react";
import { Calculator } from "lucide-react";
import type { CmsSection } from "@/lib/cms";

/** Kalkulator indikatif kebutuhan panel, bukan harga atau hasil engineering final. */
export default function Estimator({
  config,
}: {
  config: NonNullable<CmsSection["content"]["estimator"]>;
}) {
  const allowance = Math.max(0, Math.min(30, Number(config.allowancePercent ?? 0)));
  const [area, setArea] = useState(Number(config.defaultArea ?? 1));
  const [panelArea, setPanelArea] = useState(Number(config.defaultPanelArea ?? 1));
  const estimate = useMemo(
    () => Math.ceil((area * (1 + allowance / 100)) / Math.max(panelArea, 0.1)),
    [allowance, area, panelArea],
  );

  return (
    <section className="bg-navy p-6 text-white md:p-8" aria-labelledby="estimator-title">
      <Calculator className="text-gold" aria-hidden="true" />
      <h2 id="estimator-title" className="mt-4 font-display text-2xl font-bold">
        {config.title}
      </h2>
      <p className="mt-2 text-sm leading-6 text-white/70">
        {config.description}
      </p>
      <div className="mt-6 grid gap-4 sm:grid-cols-2">
        <label className="text-sm font-bold">
          {config.areaLabel}
          <input
            className="field mt-2 text-ink"
            type="number"
            min="1"
            max="100000"
            value={area}
            onChange={(event) => setArea(Number(event.target.value))}
          />
        </label>
        <label className="text-sm font-bold">
          {config.panelAreaLabel}
          <input
            className="field mt-2 text-ink"
            type="number"
            min="0.1"
            max="20"
            step="0.1"
            value={panelArea}
            onChange={(event) => setPanelArea(Number(event.target.value))}
          />
        </label>
      </div>
      <p className="mt-6 border-t border-white/20 pt-5 text-sm">
        {config.resultLabel}:{" "}
        <strong className="ml-2 font-display text-3xl text-gold">{estimate} panel</strong>
      </p>
    </section>
  );
}
