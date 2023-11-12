import { Paginator } from './types'

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
