#!/usr/bin/env python3
# coding: utf-8

"""
Implement an LDA-based RecSys engine.
"""

import sys
import numpy as np
import pandas as pd
from engine import Engine


class LDAEngine(Engine):
    def __init__(self):
        self.painting_df = pd.read_csv("data/text_data/ng-dataset.csv")
        self.cos_mat = np.load("data/matrices/lda/cosine-mat.npy")

    def index2pid(self, painting_df, index):
        """From the index, returns the painting ID from the paintings dataframe
        Input:
                painting_df: dataframe of paintings
                index: index of the painting in the dataframe
        Output:
                pid: return the painting ID (e.g: 000-02T4-0000 )
        """
        try:
            pid = painting_df.iloc[index].painting_id
        except IndexError as ie:
            pid = "Index '" + index + "' not found in dataset."
        return pid

    def indexlist2pidlist(self, painting_df, index_list):
        """From a list of indexes, returns the painting IDs
        Input:
                painting_df: dataframe of paintings
                index_list: list of the painting indexes in the dataframe
        Output:
                pid: list of paintings ID
        """
        pids_list = [self.index2pid(painting_df, index) for index in index_list]
        return pids_list

    def pid2index(self, painting_df, painting_id):
        """From the painting ID, returns the index of the painting in the painting dataframe
        Input:
                painting_df: dataframe of paintings
                painting_list: list of paintings ID (e.g ['000-02T4-0000', '000-03WC-0000...'])
        Output:
                index_list: list of the paintings indexes in the dataframe (e.g [32, 45, ...])
        """
        try:
            index = painting_df.loc[painting_df["painting_id"] == painting_id].index[0]
        except IndexError as ie:
            index = "Painting ID '" + painting_id + "' not found in dataset."
        return index

    def pidlist2indexlist(self, painting_df, painting_list):
        """From a list of painting ID, returns the indexes of the paintings
        Input:
                painting_df: dataframe of paintings
                painting_list: list of paintings ID (e.g ['000-02T4-0000', '000-03WC-0000...'])
        Output:
                index_list: list of the paintings indexes in the dataframe (e.g [32, 45, ...])
        """
        index_list = [
            self.pid2index(painting_df, painting_id) for painting_id in painting_list
        ]
        return index_list

    def retrieval(self, preferences, n=3):
        """Recommand paintings for a user based on a list of items that were liked
        Input:
                painting_list: list of paintings index liked by a user
                cos_mat: Cosine Similarity Matrix
                n: number of recommendation wanted
        Output:
                a list of indexes for recommended paintings
        """

        painting_list, weights = self.unpack_prefs(preferences)

        n_painting = len(painting_list)

        score_list = []
        index_list = self.pidlist2indexlist(self.painting_df, painting_list)

        weights = np.asarray(weights).reshape(-1, 1)
        for index in index_list:
            score = self.cos_mat[index]
            score[index] = 0
            score_list.append(score)

        score_list = np.sum(np.multiply(weights, score_list), axis=0) / n_painting
        top_n_index = sorted(
            range(len(score_list)), key=lambda i: score_list[i], reverse=True
        )[:n]

        top_n_pids = self.indexlist2pidlist(self.painting_df, top_n_index)

        return list(top_n_pids)


if __name__ == "__main__":
    preferences = {
        "000-02Q1-0000": 5,
        "000-04H4-0000": 5,
        "000-02W4-0000": 5,
        "000-02ND-0000": 5,
        "000-03N1-0000": 5,
        "000-019R-0000": 5,
        "000-03TQ-0000": 5,
        "000-019X-0000": 5,
        "000-02OU-0000": 5,
    }

    eng = LDAEngine()
    eng.retrieval(preferences, n=9)
