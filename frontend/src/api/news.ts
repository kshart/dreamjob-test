import { Paginator } from './types'
import { User } from './user'

interface CreateNews {
  slug: string
  title: string
  is_draft: boolean
  description: string
}

interface UpdateNews {
  title?: string
  description?: string
}

export interface News {
  id: number
  slug: string
  /**
   * Название новости
   */
  title: string
  /**
   * Описание в md формате
   */
  description: string
  /**
   * Новость видит только владелец
   */
  is_draft: boolean
  /**
   * Владелец новости
   */
  author_id: number
  created_at: string
  updated_at: string
}

interface PaginatorNews extends Paginator<News> {
  users: User[]
}
interface NewsWithUser extends News {
  user?: User
}

export default {
  create (fields: CreateNews) {
    return fetch('/api/news/create', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(fields)
    })
      .then(response => {
        if (!response.ok) {
          response.json()
          return null
        }
        return response.json()
      })
      .catch(error => {
        console.error(error)
        return null
      })
  },
  search (fts: string, page: number, perPage: number): Promise<Paginator<NewsWithUser>> {
    return fetch(`/api/news/search?fts=${encodeURIComponent(fts)}&page=${page}&limit=${perPage}`)
      .then(async (response) => {
        return {
          data: await response.json(),
          users: [],
          page: Number(response.headers.get('x-pagination-current-page')),
          perPage: Number(response.headers.get('x-pagination-per-page')),
          total: Number(response.headers.get('x-pagination-total-count')),
          totalPages: Number(response.headers.get('x-pagination-page-count')),
        }
      })
      .then((paginator: PaginatorNews) => {
        const usersMap = new Map<number, User>()
        for (const user of paginator.users) {
          usersMap.set(user.id, user)
        }
        for (const news of paginator.data) {
          (news as NewsWithUser).user = usersMap.get(news.author_id)
        }
        return paginator
      })
  },
  update (id: number, fields: UpdateNews) {
    return fetch(`/api/news/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(fields)
    })
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
  },
}
