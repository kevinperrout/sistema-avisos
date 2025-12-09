export const API_BASE = "/api";

async function http<T>(input: RequestInfo, init?: RequestInit): Promise<T> {
  const headersPadrao = {
    "Content-Type": "application/json",
    ...(init?.headers || {}),
  };

  const res = await fetch(input, {
    headers: headersPadrao,
    credentials: "include",
    ...init,
  });

  const dadosJson = await res.json().catch(() => null);

  if (!res.ok) {
    const mensagemErro = dadosJson?.erro || res.statusText;
    throw new Error(mensagemErro);
  }

  return dadosJson as T;
}

export const api = {
  get: <T>(url: string) => http<T>(`${API_BASE}${url}`),
  post: <T>(url: string, body?: unknown) => http<T>(`${API_BASE}${url}`, {
      method: "POST",
      body: JSON.stringify(body || {}),
  }),
};