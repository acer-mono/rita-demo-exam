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
            return send_json_bad_request([
                'errors' => [
                    'Укажите название категории'
                ]
            ]);
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
            return send_json_bad_request([
                'errors' => [
                    'Необходимо указать название категории'
                ]
            ]);
        }

        try {
            $category = $this->categories->getById((int) $id);

            $category->changeName($data['name']);

            return send_json([
                'categoryId' => $this->categories->store($category)
            ]);
        } catch (ApplicationCategoryNotFoundException $exception) {
            return send_json_bad_request([
                'errors' => [
                    $exception->getMessage()
                ]
            ]);
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
