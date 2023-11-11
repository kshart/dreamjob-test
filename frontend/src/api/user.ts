import { Paginator } from './types'

interface CreateUser {
  name: string
  phone: string
  password: string
  email?: string
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
      .then(async (response) => {
        return {
          data: await response.json(),
          page: Number(response.headers.get('x-pagination-current-page')),
          perPage: Number(response.headers.get('x-pagination-per-page')),
          total: Number(response.headers.get('x-pagination-total-count')),
          totalPages: Number(response.headers.get('x-pagination-page-count')),
        }
      })
  },
}
