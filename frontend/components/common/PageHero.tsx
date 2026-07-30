import Image from "next/image";

type PageHeroProps = {
  eyebrow: string;
  title: string;
  description: string;
  image?: string;
};

/** Hero internal ringkas dengan sinyal visual yang relevan. */
export default function PageHero({ eyebrow, title, description, image }: PageHeroProps) {
  return (
    <section className="relative min-h-[480px] overflow-hidden bg-navy-dark text-white">
      {image && (
        <Image
          src={image}
          alt=""
          fill
          priority
          sizes="100vw"
          className="object-cover opacity-35"
        />
      )}
      <div className="absolute inset-0 bg-[linear-gradient(90deg,rgba(10,37,50,.92),rgba(10,37,50,.55))]" />
      <div className="container-site relative flex min-h-[480px] items-end pb-16 pt-32">
        <div className="max-w-3xl">
          <p className="eyebrow">{eyebrow}</p>
          <h1 className="mt-4 font-display text-[clamp(2.5rem,6vw,4.7rem)] font-bold leading-[1.05]">
            {title}
          </h1>
          <p className="mt-5 max-w-2xl text-base leading-8 text-white/75">{description}</p>
        </div>
      </div>
    </section>
  );
}
