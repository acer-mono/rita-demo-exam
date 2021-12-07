<?php
declare(strict_types=1);

/**
 * Контроллер для управления категориями заявок.
 */
final class ApplicationCategoryController
{
    private $fetchCategoriesQuery;
    private $categories;

    public function __construct(
        FetchApplicationCategoriesQuery $fetchCategoriesQuery,
        ApplicationCategoryRepository $categories
    ) {
        $this->fetchCategoriesQuery = $fetchCategoriesQuery;
        $this->categories = $categories;
    }

    /**
     * Выводит список категорий.
     *
     * @return callable
     */
    public function list(): callable
    {
        return send_json($this->fetchCategoriesQuery->execute());
    }

    /**
     * Создает новую категорию.
     *
     * @return callable
     */
    public function create(): callable
    {
        $data = get_json_input();

        if (empty($data['name'])) {
            $response = [
                'errors' => [
                    'Укажите название категории'
                ]
            ];

            return send_json($response, 400);
        }

        return send_json([
            'categoryId' => $this->categories->store(new ApplicationCategory($data['name']))
        ]);
    }

    /**
     * Обновляет имеющуюся категорию.
     *
     * @param int $id
     * @return callable
     */
    public function update($id): callable
    {
        $data = get_json_input();

        if (empty($data['name'])) {
            $response = [
                'errors' => [
                    'Необходимо указать название категории'
                ]
            ];

            return send_json($response, 400);
        }

        try {
            $category = $this->categories->getById((int) $id);

            $category->changeName($data['name']);

            return send_json([
                'categoryId' => $this->categories->store($category)
            ]);
        } catch (ApplicationCategoryNotFoundException $exception) {
            $response = [
                'errors' => [
                    $exception->getMessage()
                ]
            ];

            return send_json($response, 400);
        }
    }

    /**
     * Удаляет категорию с указанным идентификатором.
     *
     * @param int $id
     * @return callable
     */
    public function delete($id): callable
    {
        $this->categories->removeById((int) $id);

        return send_json([
            //
        ]);
    }
}
