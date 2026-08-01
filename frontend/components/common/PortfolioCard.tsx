import Image from "next/image";
import Link from "next/link";
import MediaPlaceholder from "@/components/common/MediaPlaceholder";
import type { PortfolioItem } from "@/lib/cms-types";

/** Kartu portofolio dengan informasi proyek tetap terbaca tanpa hover. */
export default function PortfolioCard({
  item,
  onOpen,
  href,
}: {
  item: PortfolioItem;
  onOpen?: () => void;
  href?: string;
}) {
  const content = (
    <>
      {item.image ? (
        <Image
          src={item.image}
          alt={item.imageAlt ?? `${item.title}, proyek GRC di ${item.location}`}
          fill
          sizes="(max-width: 768px) 100vw, 50vw"
          className="object-cover opacity-85 transition duration-500 group-hover:scale-105 group-hover:opacity-65"
        />
      ) : (
        <MediaPlaceholder label={`Foto ${item.title} belum tersedia`} />
      )}
      {item.featured && (
        <span className="absolute right-4 top-4 rounded-sm bg-gold px-3 py-1 text-xs font-bold text-navy-dark">
          Unggulan
        </span>
      )}
      <span className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 to-transparent p-5 pt-14 text-white">
        <span className="block text-xs font-bold uppercase text-gold-light">
          {item.category} / {item.year}
        </span>
        <span className="mt-1 block font-display text-xl font-bold">{item.title}</span>
        <span className="mt-1 block text-sm text-white/75">{item.location}</span>
      </span>
    </>
  );

  if (href) {
    return (
      <Link
        href={href}
        className="group relative block aspect-[4/3] w-full overflow-hidden rounded-md bg-navy text-left"
        aria-label={`Lihat proyek ${item.title}`}
      >
        {content}
      </Link>
    );
  }

  return (
    <button
      type="button"
      onClick={onOpen}
      className="group relative block aspect-[4/3] w-full overflow-hidden rounded-md bg-navy text-left"
      aria-label={`Lihat proyek ${item.title}`}
    >
      {content}
    </button>
  );
}
