#!/usr/bin/env python3
# coding: utf-8

"""
Implement a fusion retrieval engine (BERT + LDA or BERT + ResNet or LDA + ResNet).

Authors:
- Bereket A. Yilma <name.surname@uni.lu>
- Luis A. Leiva <name.surname@uni.lu>
"""

from collections import defaultdict
from flask import Flask, request, jsonify
from engine import Engine


def reciprocal_rank_fusion(*ranks, top_n=10, decay=0, model_weights=None):
    """
    Combine any number of rankings as described in Cormak et al. (SIGIR'09)
    <https://doi.org/10.1145/1571941.1572114>

    The `model_weights` arg (iterable) will give more importance to some rankings over the others.
    """
    ranking = defaultdict(float)
    for r, rank in enumerate(ranks):
        num = model_weights[r] if model_weights and model_weights[r] > 0 else 1
        for i, item in enumerate(rank):
            ranking[item] += num / (i + 1 + decay)
    # Descending sort, as higher scores means more agreement between rankings.
    ranked = sorted(ranking, key=ranking.get, reverse=True)
    return ranked[:top_n]


class FusionEngine(Engine):
    """
    Implement a combined retrieval engine using late fusion.
    The `model_weights` arg (iterable) will give more importance to some rankings over the others;
    i.e. how much each model should contribute to the final ranking?
    """

    def __init__(self, models, model_weights=None):
        # Ensure each model implements the expected engine API.
        for model in models:
            retrieval_fn = getattr(model, "retrieval", None)
            if not callable(retrieval_fn):
                raise ValueError('Model "{}" must implement a `retrieval()` method.')

        self.models = models
        self.model_weights = model_weights


    def retrieval(self, preferences, n=3, top_k=100):
        """
        Return ranked list of images that are closest to the query painytings list.
        n is the number of items in the fused ranking.
        top_k is the number of items to rank BEFORE doing the fusion.
        """
        rankings = []
        for model in self.models:
            # Retrieve as much images as possible, in order to optimize the results.
            # Later on, keep the top-N as the final fused ranking.
            rank = model.retrieval(preferences, n=top_k)
            rankings.append(rank)

        return reciprocal_rank_fusion(
            *rankings, top_n=n, model_weights=self.model_weights
        )


if __name__ == "__main__":
    from lda_engine import LDAEngine
    from bert_engine import BertEngine

    lda = LDAEngine()
    bert = BertEngine()

    preferences = {
      "000-02Q1-0000":1,
      "000-04H4-0000":1,
      "000-02W4-0000":1,
      "000-02ND-0000":1,
      "000-03N1-0000":5,
      "000-019R-0000":1,
      "000-03TQ-0000":1,
      "000-019X-0000":1,
      "000-02OU-0000":1
    }

    eng = FusionEngine(models=(lda, bert), model_weights=(0.50, 0.50))
    eng.retrieval(preferences, n=9)
