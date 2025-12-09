export interface User {
  id: number;
  name: string;
  email: string;
}

export interface Contact {
  id: number;
  name: string;
  email: string;
  address: string;
  phones?: Phone[];
}

export interface Phone {
  id: number;
  number: string;
}

export interface AuthResponse {
  data: User;
}

export interface ContactsResponse {
  data: Contact[];
  total: number;
  page: number;
  limit: number;
}