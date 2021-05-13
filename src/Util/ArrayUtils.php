<?php
namespace Rmx351\Commons\Util;

abstract class ArrayUtils
{
    public static function diff(array $oldItems, array $newItems, $getId = null)
    {
        if ($getId === null) {
            $getId = static function ($item) {
                return $item['id'];
            };
        }

        $oldItemIds = array_map($getId, $oldItems);
        $newItemIds = array_map($getId, $newItems);

        $result = ['removing' => [], 'inserting' => [], 'updating' => []];

        $removingIds = array_diff($oldItemIds, $newItemIds);
        array_walk($oldItems, static function ($item) use (&$result, $getId, $removingIds) {
            if (in_array($getId($item), $removingIds, true)) {
                $result['removing'][] = $item;
            }
        });

        $insertingIds = array_diff($newItemIds, $oldItemIds);
        array_walk($newItems, static function ($item) use (&$result, $getId, $insertingIds) {
            if (in_array($getId($item), $insertingIds, true)) {
                $result['inserting'][] = $item;
            }
        });

        $updatingIds = array_intersect($oldItemIds, $newItemIds);
        foreach ($oldItems as $oldItem) {
            foreach ($newItems as $newItem) {
                $oldItemId = $getId($oldItem);
                $newItemId = $getId($newItem);
                if (($oldItemId === $newItemId) && in_array($oldItemId, $updatingIds, true) && in_array($newItemId, $updatingIds, true)) {
                    $result['updating'][] = ['old' => $oldItem, 'new' => $newItem];
                }
            }
        }

        return $result;
    }
}