import Image from "next/image";
import type { PortfolioItem } from "@/lib/constants";

/** Kartu portofolio dengan informasi proyek tetap terbaca tanpa hover. */
export default function PortfolioCard({
  item,
  onOpen,
}: {
  item: PortfolioItem;
  onOpen?: () => void;
}) {
  return (
    <button
      type="button"
      onClick={onOpen}
      className="group relative block aspect-[4/3] w-full overflow-hidden rounded-md bg-navy text-left"
      aria-label={`Lihat proyek ${item.title}`}
    >
      <Image
        src={item.image}
        alt={`${item.title}, proyek GRC di ${item.location}`}
        fill
        sizes="(max-width: 768px) 100vw, 50vw"
        className="object-cover opacity-85 transition duration-500 group-hover:scale-105 group-hover:opacity-65"
      />
      <span className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 to-transparent p-5 pt-14 text-white">
        <span className="block text-xs font-bold uppercase text-gold-light">
          {item.category} / {item.year}
        </span>
        <span className="mt-1 block font-display text-xl font-bold">{item.title}</span>
        <span className="mt-1 block text-sm text-white/75">{item.location}</span>
      </span>
    </button>
  );
}
