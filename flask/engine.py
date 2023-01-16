#!/usr/bin/env python3
# coding: utf-8

"""
Generic engine class.

Authors:
- Bereket A. Yilma <name.surname@uni.lu>
- Luis A. Leiva <name.surname@uni.lu>
"""

import sys

class Engine:

    def unpack_prefs(self, preferences):
        painting_list = []
        weights = []
        for painting, rating in preferences.items():
            painting_list.append(painting)
            weights.append(rating)
        xmin = min(weights)
        xmax = max(weights)
        for i, x in enumerate(weights):
            weights[i] = (x - xmin) / (xmax - xmin + sys.float_info.epsilon)

        return painting_list, weights
