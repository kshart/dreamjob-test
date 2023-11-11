<?php

use yii\db\Migration;

class m231111_000001_create_news_table extends Migration
{
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->unique()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'tags' => $this->json()->notNull()->defaultExpression("'[]'::jsonb"),
            'is_draft' => $this->boolean()->notNull()->defaultValue(false),
            'user_id' => $this->integer()->notNull(),
            'views' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);
        $this->execute(<<<SQL
            CREATE OR REPLACE FUNCTION news_make_tsvector(
                title TEXT,
                description TEXT,
                tags JSONB
            ) RETURNS tsvector
            LANGUAGE 'plpgsql' IMMUTABLE
            AS \$function\$
            BEGIN
                RETURN (setweight(to_tsvector(title)),'A') ||
                    setweight(jsonb_to_tsvector(tags, '"all"'), 'B') ||
                    setweight(to_tsvector(description),'C');
            END
            \$function\$;
        SQL);
        $this->execute("CREATE INDEX IF NOT EXISTS news_tsvector_idx ON news USING gin(news_make_tsvector(title, description, tags))");


    }

    public function down()
    {
        $this->dropTable('news');
        $this->execute('DROP FUNCTION news_make_tsvector');
    }
}
