import { Paginator } from './types'

interface CreateUser {
  name: string
  phone: string
  password: string
  email?: string
}

export interface Me {
  id: number
  name: string
  phone: string
  email: string
  email_verified_at: string
  created_at: string
  updated_at: string
}

export interface User {
  id: number
  name: string
  phone: string
  email: string
  email_verified_at: string
  created_at: string
  updated_at: string
}

const meInternal = (): Promise<Me> => {
  return fetch('/api/user/me')
    .then(response => {
      if (!response.ok) {
        return null
      }
      return response.json()
    })
    .catch(error => {
      console.error(error)
      return null
    })
}

export default {
  create (fields: CreateUser) {
    return fetch('/api/user/create', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(fields)
    })
      .then(({ ok, body }) => {
        if (!ok) {
          return null
        }
        return body
      })
      .catch(error => {
        console.error(error)
        return null
      })
  },
  index (page: number, perPage: number): Promise<Paginator<User>> {
    return fetch(`/api/user/index?page=${page}&limit=${perPage}`)
      .then(response => response.json())
  },
  me (): Promise<Me> {
    return (window as any).meResolve as Promise<Me>
  },
  login (phone: string, password: string) {
    return fetch('/api/user/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        phone,
        password,
      })
    })
      .then(({ ok, body }) => {
        if (!ok) {
          return null
        }
        (window as any).meResolve = meInternal()
        return body
      })
      .catch(error => {
        console.error(error)
        return null
      })
  },
  logout (): Promise<boolean> {
    return fetch('/api/user/logout', {
      method: 'POST',
    })
      .then(({ ok }) => {
        if (!ok) {
          return false
        }
        (window as any).meResolve = new Promise((resolve) => resolve(false))
        return true
      })
      .catch(error => {
        console.error(error)
        return false
      })
  },
}
