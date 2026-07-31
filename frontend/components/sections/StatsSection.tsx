/** Angka kepercayaan utama perusahaan. */
export default function StatsSection({
  stats,
}: {
  stats: Array<{ value: string; label: string }>;
}) {
  return (
    <section id="statistik" className="bg-navy text-white" aria-label="Statistik perusahaan">
      <div className="container-site grid grid-cols-2 divide-x divide-white/15 py-10 lg:grid-cols-4">
        {stats.map((stat) => (
          <div key={stat.label} className="px-3 py-5 text-center md:px-6">
            <strong className="font-display text-4xl text-gold md:text-5xl">{stat.value}</strong>
            <span className="mt-2 block text-xs font-semibold text-white/70 md:text-sm">
              {stat.label}
            </span>
          </div>
        ))}
      </div>
    </section>
  );
}
