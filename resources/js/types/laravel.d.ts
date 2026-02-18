/**
 * Generated TypeScript types for Laravel pagination and application models.
 * Keep these in sync with your migrations and models.
 */

export interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

export interface LaravelPagination<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number | null;
  last_page: number;
  last_page_url: string;
  links: PaginationLink[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
}

// --- Model types inferred from migrations in database/migrations ---

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
  password: string | null;
  remember_token: string | null;
  two_factor_secret: string | null;
  two_factor_recovery_codes: string | null;
  two_factor_confirmed_at: string | null;
  created_at: string | null;
  updated_at: string | null;
}

export interface Game {
  id: number;
  title: string;
  description: string | null;
  slug: string;
  created_at: string | null;
  updated_at: string | null;
}

export interface GamePayload {
  id: number;
  game_id: number;
  title: string;
  // `payload` is stored as JSON in the DB; use a matching shape in your app where appropriate.
  payload: any;
  created_at: string | null;
  updated_at: string | null;
}

export interface GameSubmission {
  id: number;
  game_id: number;
  game_payload_id: number;
  user_id: number;
  submission: any | null;
  result: string | null;
  created_at: string | null;
  updated_at: string | null;
}

export interface PostCategory {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  created_at: string | null;
  updated_at: string | null;
}

export interface Post {
  id: number;
  post_category_id: number;
  title: string;
  slug: string;
  content: string;
  excerpt: string | null;
  tags: string | null;
  user_id: number;
  published_at: string | null;
  deleted_at?: string | null; // soft deletes
  created_at: string | null;
  updated_at: string | null;
}

// Convenience aliases
export type PaginatedUsers = LaravelPagination<User>;
export type PaginatedGames = LaravelPagination<Game>;
export type PaginatedGamePayloads = LaravelPagination<GamePayload>;
export type PaginatedGameSubmissions = LaravelPagination<GameSubmission>;
export type PaginatedPostCategories = LaravelPagination<PostCategory>;
export type PaginatedPosts = LaravelPagination<Post>;
