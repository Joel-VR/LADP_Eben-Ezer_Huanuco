-- Iglesia Eben-Ezer — Postgres schema (Neon)
-- Apply with: psql "$DATABASE_URL" -f db/schema.sql

CREATE TABLE IF NOT EXISTS "admin_users" (
  id SERIAL PRIMARY KEY,
  username TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS "settings" (
  id SERIAL PRIMARY KEY,
  "key" TEXT UNIQUE NOT NULL,
  value TEXT NOT NULL DEFAULT '',
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS "ministry_content" (
  id SERIAL PRIMARY KEY,
  slug TEXT UNIQUE NOT NULL,
  title TEXT NOT NULL DEFAULT '',
  description TEXT NOT NULL DEFAULT '',
  leaders TEXT NOT NULL DEFAULT '',
  activities TEXT NOT NULL DEFAULT '',
  videos TEXT NOT NULL DEFAULT '',
  photos TEXT NOT NULL DEFAULT '',
  social_media JSONB,
  live_streams JSONB,
  portfolio_items JSONB,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS "events" (
  id SERIAL PRIMARY KEY,
  title TEXT NOT NULL DEFAULT '',
  description TEXT NOT NULL DEFAULT '',
  "date" TEXT NOT NULL DEFAULT '',
  image TEXT NOT NULL DEFAULT '',
  active INTEGER NOT NULL DEFAULT 1,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS "contact_messages" (
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL DEFAULT '',
  phone TEXT NOT NULL DEFAULT '',
  email TEXT NOT NULL DEFAULT '',
  message TEXT NOT NULL DEFAULT '',
  "read" INTEGER NOT NULL DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS "decisiones" (
  id SERIAL PRIMARY KEY,
  nombres TEXT NOT NULL DEFAULT '',
  apellidos TEXT NOT NULL DEFAULT '',
  edad TEXT NOT NULL DEFAULT '',
  telefono TEXT NOT NULL DEFAULT '',
  direccion TEXT NOT NULL DEFAULT '',
  departamento TEXT NOT NULL DEFAULT '',
  provincia TEXT NOT NULL DEFAULT '',
  distrito TEXT NOT NULL DEFAULT '',
  email TEXT NOT NULL DEFAULT '',
  entrego INTEGER NOT NULL DEFAULT 0,
  reconcilio INTEGER NOT NULL DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Seed is handled by src/database.php db_init(), but defaults for fresh psql:
INSERT INTO "settings" ("key", value) VALUES
  ('church_name', 'Iglesia Eben-Ezer'),
  ('church_email', 'sedenacional@ladp.org.pe'),
  ('church_phone', '+51 913 629 693 | (01) 4236207'),
  ('church_address', 'Av. Colombia 325, Pueblo Libre'),
  ('church_hours', 'Cierra a las 6 p.m.'),
  ('facebook_url', 'https://www.facebook.com/profile.php?id=100067152944125&locale=es_LA')
ON CONFLICT ("key") DO NOTHING;

INSERT INTO "ministry_content" (slug, title, description) VALUES
  ('ministerio-jovenes', 'Ministerio de Jóvenes', 'Formación, servicio y acompañamiento espiritual'),
  ('ministerio-ninos', 'Ministerio de Niños', 'Enseñanza bíblica y acompañamiento para la niñez'),
  ('ministerio-familia', 'Ministerio de Familia', 'Fortalecimiento de hogares y relaciones saludables'),
  ('ministerio-evangelismo', 'Ministerio de Evangelismo', 'Alcance y proclamación del evangelio'),
  ('direccion-misiones', 'Dirección de Misiones', 'Expansión, anexos y apoyo misionero'),
  ('direccion-comunicaciones', 'Dirección de Comunicaciones', 'Comunicación institucional y contenido digital')
ON CONFLICT (slug) DO NOTHING;
