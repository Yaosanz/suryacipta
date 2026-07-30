"use client";

import { useMemo, useState } from "react";
import { Calculator } from "lucide-react";

/** Kalkulator indikatif kebutuhan panel, bukan harga atau hasil engineering final. */
export default function Estimator() {
  const [area, setArea] = useState(20);
  const [panelArea, setPanelArea] = useState(1.2);
  const estimate = useMemo(() => Math.ceil((area * 1.05) / Math.max(panelArea, 0.1)), [area, panelArea]);

  return (
    <section className="bg-navy p-6 text-white md:p-8" aria-labelledby="estimator-title">
      <Calculator className="text-gold" aria-hidden="true" />
      <h2 id="estimator-title" className="mt-4 font-display text-2xl font-bold">
        Kalkulator kebutuhan panel
      </h2>
      <p className="mt-2 text-sm leading-6 text-white/70">
        Estimasi jumlah panel termasuk allowance 5%. Verifikasi lapangan tetap diperlukan.
      </p>
      <div className="mt-6 grid gap-4 sm:grid-cols-2">
        <label className="text-sm font-bold">
          Luas area (m2)
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
          Luas per panel (m2)
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
        Estimasi kebutuhan: <strong className="ml-2 font-display text-3xl text-gold">{estimate} panel</strong>
      </p>
    </section>
  );
}
