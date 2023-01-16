#!/usr/bin/env python3
# coding: utf-8

"""
Implement a ResNet-based RecSys engine.

Authors:
- Bereket A. Yilma <name.surname@uni.lu>
- Luis A. Leiva <name.surname@uni.lu>
"""

import os
import re
import pickle
import numpy as np
import torch
from torchmetrics.functional import pairwise_cosine_similarity
from engine import Engine


class ResNetEngine(Engine):
    def __init__(self):
        # self.painting_ids = None
        # self.resnet_features = None
        self.load_data()

    def load_data(self):
        # Load dataset.
        dataset = pickle.load(
            open("data/matrices/resnet/paintings_resnet_features.pickle", "rb")
        )

        # Pre-process dataset to remove missing paintings.
        painting_ids = []
        resnet_features = []
        image_files_names = os.listdir("paintings/")
        painting_ids_dict = dict.fromkeys(dataset["painting_ids"], "YES")

        for i in range(0, len(image_files_names)):
            painting_name = re.sub(".jpg", "", image_files_names[i])
            if painting_name in painting_ids_dict.keys():
                painting_ids.append(painting_name)
                idx_of_painting = dataset["painting_ids"].index(painting_name)
                resnet_features.append(dataset["resnet_features"][idx_of_painting])

        self.painting_ids = painting_ids
        self.resnet_features = resnet_features

    def get_distances(self, selected_image_names):
        """
        Input:
            -> selected_image_names: list with strings of names of the images to analyse.
        Output:
            -> distances: numpy with cosine distance between each image in selected_image_names and resnet_features.
        """
        selected = []
        top_sim_images = []

        # Get the features of the selected images using the painting_ids list.
        for i in range(len(selected_image_names)):
            feat_idx = self.painting_ids.index(selected_image_names[i])
            selected.append(self.resnet_features[feat_idx])
        selected = np.asarray(selected)
        resnet_features = np.asarray(self.resnet_features)

        # Calculate cosine distance with torch.
        selected = torch.from_numpy(selected)
        resnet_features = torch.from_numpy(resnet_features)
        cosine_distances = pairwise_cosine_similarity(selected, resnet_features)
        cosine_distances = cosine_distances.numpy()
        return cosine_distances

    def get_top_similars(self, distances, n):
        """
        Input:
            -> distances: numpy of distances from some paintings to the rest of the original dataset.
            -> painting_ids: list of names of the images.
            -> n: number of top-N most similar images to consider.
        Output:
            -> top_sim_images: list with the names of the top-N most similar images.
        """
        top_sim_images = []
        for i in range(distances.shape[0]):
            top_distances = np.argsort(distances[i, :]).reshape(-1)[:n]
            nearest_ids = [self.painting_ids[idx] for idx in top_distances]
            top_sim_images.append(nearest_ids)
        return top_sim_images, top_distances

    def get_weighted_distances(self, distances, weights):
        """
        Input:
            -> distances: numpy of distances from some paintings to the rest of the original dataset.
            -> wights: a list of weights given to each painting.
        Output:
            -> weighted_distances: numpy with weighted avg distances of shape (1, distances.shape[1]).
            -> confidences: numpy with the inverse of weighted_distances an the same shape.
        """
        assert (
            len(weights) == distances.shape[0]
        )  # Assert if num weights == num images used to calculate distance.
        weights = np.asarray(weights).reshape(-1, 1)
        N = distances.shape[0]
        weighted_distances = np.sum(np.multiply(weights, distances), axis=0) / N
        weighted_distances = np.reshape(weighted_distances, (1, -1))
        confidences = 1 - weighted_distances
        return weighted_distances, confidences

    def retrieval(self, preferences, n=3):
        """Recommand paintings for a user based on a list of items that were liked
        Input:
                painting_list: list of paintings index liked by a user
                weights: user ratings
                n: number of recommendation wanted
        Output:
                a list of indexes for recommended paintings
        """
        painting_list, weights = self.unpack_prefs(preferences)

        # Apply function and get distances and top-N most similar images.
        distances = self.get_distances(painting_list)

        # Apply the weighting mechanism.
        weighted_distances, weighted_confidences = self.get_weighted_distances(
            distances, weights
        )
        top_n_pids, top_distances = self.get_top_similars(weighted_distances, n)
        top_n_pids = [item for sublist in top_n_pids for item in sublist]
        # weighted_confidences = weighted_confidences.reshape(-1)
        # scores = np.sort(weighted_confidences[:n])[::-1]
        return list(top_n_pids)


if __name__ == "__main__":
    preferences = {
        "000-02Q1-0000": 1,
        "000-04H4-0000": 1,
        "000-02W4-0000": 1,
        "000-02ND-0000": 1,
        "000-03N1-0000": 5,
        "000-019R-0000": 1,
        "000-03TQ-0000": 1,
        "000-019X-0000": 1,
        "000-02OU-0000": 1,
    }

    eng = ResNetEngine()
    eng.retrieval(preferences, n=9)
